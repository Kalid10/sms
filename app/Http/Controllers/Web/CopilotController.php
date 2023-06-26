<?php

namespace App\Http\Controllers\Web;

use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\SchoolYear;
use App\Services\OpenAIService;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CopilotController extends Controller
{
    public function show(Request $request, TeacherService $teacherService): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|exists:batch_subjects,id',
            'month' => 'nullable|date_format:"Y-m"',
        ]);

        $teacherId = auth()->user()->teacher->id;

        $lessonPlansData = $teacherService->getLessonPlansData($request, $teacherId);
        $assessmentTypes = AssessmentType::where([
            ['school_year_id', SchoolYear::getActiveSchoolYear()->id], [
                'level_category_id', 1,
            ]])->get(['name', 'id']);

        return Inertia::render('Teacher/Copilot/Index', [
            'assessment_types' => $assessmentTypes,
            'lesson_plans_data' => $lessonPlansData,
            'questions' => Inertia::lazy(fn () => $this->generateQuestions($request, app(OpenAIService::class))),
        ]);
    }

    public function chat(Request $request, OpenAIService $openAIService): StreamedResponse
    {
        $messages = json_decode($request->input('messages'), true);

        return $openAIService->createChatStream($messages, 1000);
    }

    private function generateQuestions(Request $request, OpenAIService $openAIService): array
    {
        $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'question_source' => 'required|in:lesson-plans,custom',
            'lesson_plan_ids' => 'required_if:question_source,lesson-plans|array',
            'lesson_plan_ids.*' => 'exists:lesson_plans,id',
            'number_of_questions' => 'required|integer|min:1|max:50',
            'manual_question' => 'required_if:question_source,custom',
            'difficulty_level' => 'required|integer|min:1|max:10',
        ]);

        // Get the assessment type and batch subject
        $assessmentType = AssessmentType::find($request->assessment_type_id);
        $batchSubject = BatchSubject::find($request->batch_subject_id)->load('batch.level', 'subject');

        $finalPrompt = "\nPlease generate a mix of questions, making sure they are diverse and cover the key concepts provided.
        The difficulty of these questions should range from 1 (very easy) to 10 (very hard), with an average difficulty of { $request->difficulty_level}.
        For each question, provide the question itself, the answer, and the question type EXCLUDING MULTIPLE CHOICE. Also specify the difficulty level for each question on a scale from 1 to 10.\n";

        if ($request->question_source === 'lesson-plans') {
            $questions = $this->generateQuestionFromLessonPlan($request, $openAIService, $assessmentType, $batchSubject, $finalPrompt);
        }

        return $this->generateManualInputQuestions($request, $openAIService, $assessmentType, $batchSubject, $finalPrompt);
    }

    private function generateQuestionFromLessonPlan($request, $openAIService, $assessmentType, $batchSubject, $finalPrompt): array
    {
        $lessonPlans = LessonPlan::whereIn('id', $request->lesson_plan_ids)->get();

        $prompt = "Generate {$request->no_of_questions} {$assessmentType->name} questions for Grade {$batchSubject->batch->level->name} , subject Biology. Consider the following lesson plans:\n\n";

        // Adding topics from lesson plans to the prompt
        foreach ($lessonPlans as $lessonPlan) {
            $prompt .= "- Topic: {$lessonPlan->topic}, Key Concepts: {$lessonPlan->description}\n";
        }

        $prompt .= $finalPrompt;

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    public function generateManualInputQuestions($request, $openAIService, $assessmentType, $batchSubject, $finalPrompt): array
    {
        $prompt = "Generate {$request->number_of_questions} {$assessmentType->name} questions for Grade {$batchSubject->batch->level->name}, subject Biology, based on the following topic:\n\n";
        $prompt .= "Topic: {$request->manual_question}\n";
        $prompt .= $finalPrompt;

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function passThePromptToTheCompletionMethod($openAIService, string $prompt): array
    {
        $questionResponses = $openAIService->createCompletion($prompt);

        // Parsing the response to extract the question details
        $questions = [];
        for ($i = 0; $i < count($questionResponses); $i += 5) {
            if (isset($questionResponses[$i], $questionResponses[$i + 1], $questionResponses[$i + 2], $questionResponses[$i + 3])) {
                $questionDetails = [
                    'question' => trim(str_replace('Question:', '', $questionResponses[$i])),
                    'answer' => trim(str_replace('Answer:', '', $questionResponses[$i + 1])),
                    'question_type' => trim(str_replace('Question Type:', '', $questionResponses[$i + 2])),
                    'difficulty' => trim(str_replace('Difficulty Level:', '', $questionResponses[$i + 3])),
                ];

                $questions[] = $questionDetails;
            }
        }

        return $questions;
    }
}

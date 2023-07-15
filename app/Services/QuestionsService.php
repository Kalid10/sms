<?php

namespace App\Services;

use App\Events\QuestionGeneratorEvent;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuestionsService
{
    public function generateQuestions(array $requestData, OpenAIService $openAIService, $userId): ?array
    {
        Validator::make($requestData, [
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'batch_subject_id' => 'required|exists:batch_subjects,id',
            'question_source' => 'required|in:lesson-plans,custom',
            'lesson_plan_ids' => 'required_if:question_source,lesson-plans|array',
            'lesson_plan_ids.*' => 'exists:lesson_plans,id',
            'number_of_questions' => 'required|integer|min:1|max:50',
            'manual_question' => 'required_if:question_source,custom',
            'difficulty_level' => 'required|integer|min:1|max:10',
        ])->validate();

        // Get the assessment type and batch subject
        $assessmentType = AssessmentType::find($requestData['assessment_type_id']);
        $batchSubject = BatchSubject::find($requestData['batch_subject_id'])->load(['batch.level', 'subject']);

        $bannedLevels = explode(',', env('BANNED_LEVELS'));
        $bannedSubjects = explode(',', env('BANNED_SUBJECTS'));

        if (in_array(strtolower($batchSubject->batch->level->name), $bannedLevels)) {
            return event(new QuestionGeneratorEvent('error', 'Questions cannot be generated for this class, ci '));
        }

        if (in_array(strtolower($batchSubject->subject->name), $bannedSubjects)) {
            return event(new QuestionGeneratorEvent('error', 'Questions cannot be generated for this subject'));
        }

        // Updated final prompt
        $finalPrompt = "\nAs a proficient Grade {$batchSubject->batch->level->name} teacher specializing in {$batchSubject->subject->name}, generate a diverse mix of questions based on the key concepts provided. These questions should cover a range of difficulty levels from 1 (very easy) to 10 (very hard), with an average difficulty of {$requestData['difficulty_level']}. For each question, provide the question itself and the answer, and exclude multiple-choice question types. Also specify the difficulty level for each question on a scale from 1 to 10.\n";

        if ($requestData['question_source'] === 'lesson-plans') {
            $questions = $this->generateQuestionFromLessonPlan($requestData, $openAIService, $assessmentType, $batchSubject, $finalPrompt);

            $this->saveQuestion($questions, $requestData, $assessmentType, $userId);

            return event(new QuestionGeneratorEvent('success', 'Questions generated successfully!'));
        } else {
            $questions = $this->generateManualInputQuestions($requestData, $openAIService, $assessmentType, $batchSubject, $finalPrompt);

            $this->saveQuestion($questions, $requestData, $assessmentType, $userId);

            return event(new QuestionGeneratorEvent('success', 'Questions generated successfully!'));
        }
    }

    private function generateQuestionFromLessonPlan($requestData, $openAIService, $assessmentType, $batchSubject, $finalPrompt): array
    {
        $lessonPlans = LessonPlan::whereIn('id', $requestData['lesson_plan_ids'])->get();
        Log::info($lessonPlans);

        $prompt = "As an experienced Grade 11 Biology teacher, generate {$requestData['number_of_questions']} {$assessmentType->name} questions based on the following lesson plans:\n\n";

        // Adding topics from lesson plans to the prompt
        foreach ($lessonPlans as $lessonPlan) {
            $prompt .= "- Topic: {$lessonPlan->topic}\n";
        }

        $prompt .= "\nEnsure the questions are diverse, covering key concepts in each topic. The difficulty of these questions should range from 1 (very easy) to 10 (very hard), with an average difficulty of {$requestData['difficulty_level']}. For each question, provide the question itself, the answer, and exclude multiple-choice question types. Also specify the difficulty level for each question on a scale from 1 to 10.";

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function generateManualInputQuestions($requestData, $openAIService, $assessmentType, $batchSubject, $finalPrompt): array
    {
        $prompt = "As a proficient Grade {$batchSubject->batch->level->name} Biology teacher, generate {$requestData['number_of_questions']} {$assessmentType->name} questions based on the following topic:\n\n";
        $prompt .= "- Topic: {$requestData['manual_question']}\n";
        $prompt .= "\nEnsure the questions are diversified, covering key aspects of the topic. The difficulty of these questions should range from 1 (very easy) to 10 (very hard), with an average difficulty of {$requestData['difficulty_level']}. For each question, provide the question itself, the answer, and exclude multiple-choice question types. Also specify the difficulty level for each question on a scale from 1 to 10.";

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function passThePromptToTheCompletionMethod($openAIService, string $prompt): array
    {
        $questionResponses = $openAIService->createCompletion($prompt);

        // Parsing the response to extract the question details
        $questions = [];
        foreach ($questionResponses as $response) {
            // Checking the presence of all required fields in each question
            if (str_contains($response, 'Question:') && str_contains($response, 'Answer:') && str_contains($response, 'Question Type:') && str_contains($response, 'Difficulty Level:')) {
                $questionDetails = [
                    'question' => trim(str_replace('Question:', '', $response)),
                    'answer' => trim(str_replace('Answer:', '', $response)),
                    'question_type' => trim(str_replace('Question Type:', '', $response)),
                    'difficulty' => trim(str_replace('Difficulty Level:', '', $response)),
                ];
                $questions[] = $questionDetails;
            }
        }

        return $questions;
    }

    private function saveQuestion($questions, $requestData, $assessmentType, $userId): void
    {
        Question::create([
            'user_id' => $userId,
            'batch_subject_id' => $requestData['batch_subject_id'],
            'questions' => $questions,
            'lesson_plan_ids' => $requestData['lesson_plan_ids'],
            'assessment_type_id' => $assessmentType->id,
            'no_of_questions' => $requestData['number_of_questions'],
            'difficulty_level' => $requestData['difficulty_level'],
        ]);
    }
}

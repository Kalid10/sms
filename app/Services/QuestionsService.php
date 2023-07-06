<?php

namespace App\Services;

use App\Events\QuestionGeneratorEvent;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class QuestionsService
{
    public function generateQuestions(array $requestData, OpenAIService $openAIService, $userId): array
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
        $batchSubject = BatchSubject::find($requestData['batch_subject_id'])->load('batch.level', 'subject');

        $finalPrompt = "\nPlease generate a mix of questions, making sure they are diverse and cover the key concepts provided.
        The difficulty of these questions should range from 1 (very easy) to 10 (very hard), with an average difficulty of {$requestData['difficulty_level']}.
        For each question, provide the question itself, the answer, and the question type EXCLUDING MULTIPLE CHOICE. Also specify the difficulty level for each question on a scale from 1 to 10.\n";

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

        $prompt = "Generate {$requestData['number_of_questions']} {$assessmentType->name} questions for Grade 11 , subject Biology. Consider the following lesson plans:\n\n";

        // Adding topics from lesson plans to the prompt
        foreach ($lessonPlans as $lessonPlan) {
            $prompt .= "- Topic: {$lessonPlan->topic}";
        }

        $prompt .= $finalPrompt;

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function generateManualInputQuestions($requestData, $openAIService, $assessmentType, $batchSubject, $finalPrompt): array
    {
        $prompt = "Generate {$requestData['number_of_questions']} {$assessmentType->name} questions for Grade {$batchSubject->batch->level->name}, subject Biology, based on the following topic:\n\n";
        $prompt .= "Topic: {$requestData['manual_question']}\n";
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

    private function saveQuestion($questions, $requestData, $assessmentType, $userId)
    {
        return Question::create([
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

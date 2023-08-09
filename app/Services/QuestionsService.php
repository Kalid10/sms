<?php

namespace App\Services;

use App\Events\QuestionGeneratorEvent;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\LessonPlan;
use App\Models\Question;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuestionsService
{
    public function generateQuestions(array $requestData, OpenAIService $openAIService, $userId): ?array
    {
        try {
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

            $prompt = "\nAs a proficient `Grade {$batchSubject->batch->level->name}` teacher specializing in `{$batchSubject->subject->full_name}`, generate {$requestData['number_of_questions']} diversified questions based on the key concepts provided.
        These questions should cover a range of difficulty levels from 1 (very easy) to 10 (very hard), with an average difficulty of {$requestData['difficulty_level']}.
        For each question, provide the question itself and the answer, and exclude multiple-choice question types.
        Also specify the difficulty level for each question on a scale from 1 to 10.\n";

            if ($requestData['question_source'] === 'lesson-plans') {
                $questions = $this->generateQuestionFromLessonPlan($requestData, $openAIService, $prompt);

                $this->saveQuestion($questions, $requestData, $assessmentType, $userId);

                return event(new QuestionGeneratorEvent('success', 'Questions generated successfully!'));
            } else {
                $questions = $this->generateManualInputQuestions($requestData, $openAIService, $prompt);

                $this->saveQuestion($questions, $requestData, $assessmentType, $userId);

                return event(new QuestionGeneratorEvent('success', 'Questions generated successfully!'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return event(new QuestionGeneratorEvent('error', 'Question Generation failed!'));
        }
    }

    private function generateQuestionFromLessonPlan($requestData, $openAIService, $prompt): array
    {
        $lessonPlans = LessonPlan::whereIn('id', $requestData['lesson_plan_ids'])->get();

        // Adding topics from lesson plans to the prompt
        foreach ($lessonPlans as $lessonPlan) {
            $prompt .= "- Topic: {$lessonPlan->topic}\n";
        }

        $prompt .= "\nEnsure the questions are diverse, covering key concepts in each topic.";

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function generateManualInputQuestions($requestData, $openAIService, $prompt): array
    {
        $prompt .= "Questions should be based on the following topic:\n\n";
        $prompt .= "- Topic: {$requestData['manual_question']}\n";
        $prompt .= "\nEnsure the questions are diverse, covering key concepts in each topic.";

        // Pass the prompt to the completion method
        return $this->passThePromptToTheCompletionMethod($openAIService, $prompt);
    }

    private function passThePromptToTheCompletionMethod($openAIService, string $prompt): array
    {
        $responses = $openAIService->createCompletion($prompt);
        $groupedResponses = [];
        $currentEntry = '';

        foreach ($responses as $response) {
            // If the response is not empty, append it to the current entry
            if (! empty(trim($response))) {
                $currentEntry .= $response.' ';
            } // If the response is empty and there's a current entry, add it to the grouped responses and reset the current entry
            elseif (! empty(trim($currentEntry))) {
                $groupedResponses[] = trim($currentEntry);
                $currentEntry = '';
            }
        }

        // Add the last entry if it exists
        if (! empty(trim($currentEntry))) {
            $groupedResponses[] = trim($currentEntry);
        }

        return $groupedResponses;
    }

    private function saveQuestion($questions, $requestData, $assessmentType, $userId): array|Question
    {
        if (! count($questions)) {
            return event(new QuestionGeneratorEvent('error', 'Question Generation failed!'));
        }

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

<?php

namespace App\Services;

use App\Events\MassAssessmentEvent;
use App\Models\AssessmentMapping;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminAssessmentService
{
    public static function createAssessment(array $requestData, $userId): ?array
    {
        $levelCategoryIds = $requestData['level_category_ids'];

        try {
            // Loop through each level category id and get level ids
            foreach ($levelCategoryIds as $levelCategoryId) {
                $levelIds = Level::where('level_category_id', $levelCategoryId)->pluck('id');

                // Loop through each level id and get batch ids
                foreach ($levelIds as $levelId) {
                    $batchIds = Batch::where('level_id', $levelId)->pluck('id');

                    // Loop through each batch id and get batch subject ids
                    foreach ($batchIds as $batchId) {
                        $batchSubjectIds = BatchSubject::where('batch_id', $batchId)->pluck('id');

                        // Loop through each batch subject id and create assessment
                        foreach ($batchSubjectIds as $batchSubjectId) {
                            $batchSubject = BatchSubject::find($batchSubjectId);

                            $batchSubject->assessments()->create(
                                [
                                    'assessment_type_id' => $requestData['assessment_type_id'],
                                    'due_date' => $requestData['due_date'],
                                    'title' => $requestData['title'],
                                    'description' => $requestData['description'],
                                    'maximum_point' => $requestData['maximum_point'],
                                    'lesson_plan_id' => $requestData['lesson_plan_id'] ?? null,
                                    'status' => $requestData['status'],
                                    'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
                                    'quarter_id' => Quarter::getActiveQuarter()->id,
                                    'created_by' => $userId,
                                ]
                            );

                        }
                    }
                }

                // Create an assessment mapping
                AssessmentMapping::create([
                    'title' => $requestData['title'],
                    'due_date' => Carbon::parse($requestData['due_date']),
                    'user_id' => $userId,
                    'level_category_id' => $levelCategoryId,
                    'assessment_type_id' => $requestData['assessment_type_id'],
                ]);
            }

            return event(new MassAssessmentEvent('success', 'You have successfully created assessment'));
        } catch (Exception $exception) {
            Log::info('Assessment Exception');
            Log::info($exception->getMessage());

            return event(new MassAssessmentEvent('error', 'Creating assessments failed!'));
        }

    }
}

<?php

namespace Database\Seeders\Simulation;

use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\Quarter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the active Quarter,
        // And then through each batch subject,
        // And then through each assessment type
        // Create an assessment

        $quarter = Quarter::getActiveQuarter()->load('semester.schoolYear');

        $quarterDays = Carbon::parse($quarter->end_date)
            ->diffInDays(Carbon::parse($quarter->start_date));

        BatchSubject::active(['batch.level.levelCategory'])->each(function ($batchSubject) use ($quarter, $quarterDays) {
            AssessmentType::where([
                'school_year_id' => $quarter->semester->schoolYear->id,
                'level_category_id' => $batchSubject->batch->level->levelCategory->id,
            ])->each(function ($assessmentType) use ($batchSubject, $quarter, $quarterDays) {
                $totalAssessments = $assessmentType->customizable ?
                    rand(5, 10) :
                    rand($assessmentType->min_assessments, $assessmentType->max_assessments);

                $daysBetweenAssessments = floor($quarterDays / $totalAssessments);

                // Reset assessment offset days between
                // assessments before each assessment type
                $offsetDays = rand(max(0, $daysBetweenAssessments - rand(1, 7)), $daysBetweenAssessments);

                for ($i = 1; $i <= $totalAssessments; $i++) {
                    $maximumPoint = rand(10, 50);
                    $maximumPoint = $maximumPoint - $maximumPoint % 5;

                    Assessment::create([
                        'assessment_type_id' => $assessmentType->id,
                        'batch_subject_id' => $batchSubject->id,
                        'quarter_id' => $quarter->id,
                        'due_date' => Carbon::parse($quarter->start_date)->addDays($offsetDays),
                        'title' => $assessmentType->name.' #'.$i,
                        'description' => fake()->sentences(3, true),
                        'maximum_point' => $maximumPoint,
                        'lesson_plan_id' => null,
                        'status' => Assessment::STATUS_COMPLETED,
                    ]);

                    $offsetDays = $offsetDays + rand(max(0, $daysBetweenAssessments - rand(1, 7)), $daysBetweenAssessments);
                }
            });
        });
    }
}

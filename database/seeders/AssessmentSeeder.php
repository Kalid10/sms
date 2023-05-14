<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = Teacher::find(1)
            ->load([
                'batchSubjects' => function ($query) {
                    $query->whereHas('schoolYear', function ($query) {
                        $query->where('school_years.id', SchoolYear::getActiveSchoolYear()->id);
                    });
                },
                'batchSubjects.batch.level.levelCategory.assessmentTypes']);

        if ($teacher) {
            $batchSubjects = $teacher->batchSubjects;
            $activeQuarter = Quarter::getActiveQuarter();

            for ($i = 1; $i <= 20; $i++) {
                $batchSubject = $batchSubjects->random();

                $assessmentTypes = $batchSubject->batch->level->levelCategory
                    ->assessmentTypes;

                $assessmentType = $assessmentTypes->random();

                Assessment::create([
                    'batch_subject_id' => $batchSubject->id,
                    'assessment_type_id' => $assessmentType->id,
                    'quarter_id' => $activeQuarter->id,
                    'due_date' => now()->addDays($i * 2),
                    'title' => "Assessment {$i}",
                    'description' => "This is assessment {$i}.",
                    'maximum_point' => rand(10, 100),
                    'status' => fake()->randomElement([
                        Assessment::STATUS_CLOSED,
                        Assessment::STATUS_COMPLETED,
                        Assessment::STATUS_PUBLISHED,
                        Assessment::STATUS_MARKING,
                        Assessment::STATUS_DRAFT,
                    ]),
                ]);
            }
        }
    }
}

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
    //    public function run(): void
    //    {
    //        $teacher = Teacher::find(1)
    //            ->load([
    //                'batchSubjects' => function ($query) {
    //                    $query->whereHas('schoolYear', function ($query) {
    //                        $query->where('school_years.id', SchoolYear::getActiveSchoolYear()->id);
    //                    });
    //                },
    //                'batchSubjects.batch.level.levelCategory.assessmentTypes'],
    //                'user'
    //            );
    //
    //        if ($teacher) {
    //            $batchSubjects = $teacher->batchSubjects;
    //            $activeQuarter = Quarter::getActiveQuarter();
    //
    //            for ($i = 1; $i <= 20; $i++) {
    //                $batchSubject = $batchSubjects->random();
    //
    //                $assessmentTypes = $batchSubject->batch->level->levelCategory
    //                    ->assessmentTypes;
    //
    //                $assessmentType = $assessmentTypes->random();
    //
    //                if ($assessmentType->is_admin_controlled) {
    //                    continue;
    //                }
    //
    //                Assessment::create([
    //                    'batch_subject_id' => $batchSubject->id,
    //                    'assessment_type_id' => $assessmentType->id,
    //                    'quarter_id' => $activeQuarter->id,
    //                    'due_date' => now()->addDays($i * 2),
    //                    'title' => "Assessment {$i}",
    //                    'description' => "This is assessment {$i}.",
    //                    'maximum_point' => rand(10, 100),
    //                    'created_by' => $teacher->user->id,
    //                    'status' => fake()->randomElement([
    //                        // Uncomment the following statuses whenever needed
    //                        Assessment::STATUS_CLOSED,
    //                        Assessment::STATUS_COMPLETED,
    //                        Assessment::STATUS_PUBLISHED,
    //                        Assessment::STATUS_MARKING,
    //                        Assessment::STATUS_DRAFT,
    //                    ]),
    //                ]);
    //            }
    //        }
    //    }

    //

    public function run()
    {
        $teacher = Teacher::find(50)
            ->load([
                'batchSubjects' => function ($query) {
                    $query->whereHas('schoolYear', function ($query) {
                        $query->where('school_years.id', SchoolYear::getActiveSchoolYear()->id);
                    });
                },
                'batchSubjects.batch.level.levelCategory.assessmentTypes'],
                'user'
            );

        if ($teacher) {
            $batchSubjects = $teacher->batchSubjects;
            $activeQuarter = Quarter::getActiveQuarter();

            // Use the fakeData method to generate real data
            $data = $this->fakeData();

            foreach ($data as $item) {
                $batchSubject = $batchSubjects->random();
                Assessment::create(array_merge($item, [
                    'batch_subject_id' => $batchSubject->id,
                    'quarter_id' => $activeQuarter->id,
                    'created_by' => $teacher->user->id,
                ],

                ));
            }
        }

    }

    protected function fakeData()
    {
        return [
            [
                'assessment_type_id' => 3,
                'title' => 'Test one',
                'description' => 'This test is out of 20 points.',
                'maximum_point' => 20,
                'due_date' => now()->addDays(0),
                'status' => Assessment::STATUS_PUBLISHED,
            ],
            [
                'assessment_type_id' => 8,
                'title' => 'Test two',
                'description' => 'This test is out of 20 points.',
                'maximum_point' => 20,
                'due_date' => now()->addDays(0),
                'status' => Assessment::STATUS_MARKING,
            ],
            [
                'assessment_type_id' => 4,
                'title' => 'Mid Term Exam',
                'description' => 'This is Mid Term Exam',
                'maximum_point' => 20,
                'due_date' => now()->addDays(30),
                'status' => Assessment::STATUS_DRAFT,
            ],
            [
                'assessment_type_id' => 10,
                'title' => 'Final Exam',
                'description' => 'This is Final Exam.',
                'maximum_point' => 20,
                'due_date' => now()->addDays(60),
                'status' => Assessment::STATUS_DRAFT,
            ],
        ];
    }
}

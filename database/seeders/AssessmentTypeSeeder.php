<?php

namespace Database\Seeders;

use App\Models\LevelCategory;
use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $school_year = SchoolYear::getActiveSchoolYear();

        if ($school_year) {
            $assessment_types_data = [
                [
                    'name' => 'Homework',
                    'percentage' => 20,
                    'min_assessments' => 5,
                    'max_assessments' => 10,
                    'customizable' => true,
                    'is_admin_controlled' => false,

                ],
                [
                    'name' => 'Classwork',
                    'percentage' => 20,
                    'min_assessments' => 5,
                    'max_assessments' => 10,
                    'customizable' => true,
                    'is_admin_controlled' => false,

                ],
                [
                    'name' => 'Test',
                    'percentage' => 20,
                    'min_assessments' => 3,
                    'max_assessments' => 8,
                    'customizable' => true,
                    'is_admin_controlled' => true,

                ],
                [
                    'name' => 'Mid Term Exam',
                    'percentage' => 20,
                    'min_assessments' => 1,
                    'max_assessments' => 1,
                    'customizable' => false,
                    'is_admin_controlled' => true,

                ],
                [
                    'name' => 'Final Exam',
                    'percentage' => 20,
                    'min_assessments' => 1,
                    'max_assessments' => 1,
                    'customizable' => false,
                    'is_admin_controlled' => true,

                ],
            ];

            $level_categories = LevelCategory::all();

            $level_categories->each(function ($level_category) use ($assessment_types_data, $school_year) {
                $assessment_types = collect($assessment_types_data)->map(function ($assessment_type) use ($school_year) {
                    $assessment_type['school_year_id'] = $school_year->id;

                    return $assessment_type;
                });

                $level_category->assessmentTypes()->createMany($assessment_types);
            });
        }
    }
}

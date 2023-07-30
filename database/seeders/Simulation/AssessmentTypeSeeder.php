<?php

namespace Database\Seeders\Simulation;

use App\Models\AssessmentType;
use App\Models\LevelCategory;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();

        try {
            DB::beginTransaction();

            $this->assessmentTypes()->each(function ($assessmentType) use ($schoolYear) {
                $assessmentType['school_year_id'] = $schoolYear->id;
                AssessmentType::create($assessmentType);
            });

            DB::commit();
        } catch (Exception $exception) {
            Log::info('Error while creating Assessment Types: '.$exception->getMessage());
        }
    }

    private function assessmentTypes(): Collection
    {
        $kindergarten = LevelCategory::where('name', 'Kindergarten')->first()->id;
        $primary = LevelCategory::where('name', 'Primary')->first()->id;
        $secondary = LevelCategory::where('name', 'Secondary')->first()->id;

        $assessmentTypes = collect([
            [
                'name' => 'Homework',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $kindergarten,
            ],
            [
                'name' => 'Classwork',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $kindergarten,
            ],
            [
                'name' => 'Test',
                'percentage' => 40,
                'min_assessments' => 2,
                'max_assessments' => 5,
                'customizable' => 1,
                'level_category_id' => $kindergarten,
            ],
            [
                'name' => 'Final Exam',
                'percentage' => 50,
                'min_assessments' => 1,
                'max_assessments' => 1,
                'customizable' => 0,
                'level_category_id' => $kindergarten,
            ],
        ]);

        $assessmentTypes = $assessmentTypes->merge([
            [
                'name' => 'Homework',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $primary,
            ],
            [
                'name' => 'Classwork',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $primary,
            ],
            [
                'name' => 'Test',
                'percentage' => 40,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $primary,
            ],
            [
                'name' => 'Presentation',
                'percentage' => 10,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $primary,
            ],
            [
                'name' => 'Final Exam',
                'percentage' => 40,
                'min_assessments' => 1,
                'max_assessments' => 1,
                'customizable' => 0,
                'level_category_id' => $primary,
            ],
        ]);

        return $assessmentTypes->merge([
            [
                'name' => 'Homework',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $secondary,
            ],
            [
                'name' => 'Classwork',
                'percentage' => 5,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $secondary,
            ],
            [
                'name' => 'Test',
                'percentage' => 30,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $secondary,
            ],
            [
                'name' => 'Presentation',
                'percentage' => 10,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $secondary,
            ],
            [
                'name' => 'Group Project',
                'percentage' => 10,
                'min_assessments' => null,
                'max_assessments' => null,
                'customizable' => 1,
                'level_category_id' => $secondary,
            ],
            [
                'name' => 'Final Exam',
                'percentage' => 40,
                'min_assessments' => 1,
                'max_assessments' => 1,
                'customizable' => 0,
                'level_category_id' => $secondary,
            ],
        ]);
    }
}

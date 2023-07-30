<?php

namespace Database\Seeders\Simulation;

use App\Models\Batch;
use App\Models\Level;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;

class BatchSubjectSeeder extends SubjectSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = Subject::all();
        $weeklyFrequency = 4;

        $allSubjects = $this->filterSubjectsByTag('All Levels', $subjects);
        $this->filterBatchesByLevelNames($this->allLevelNames())->each(function ($batch) use ($weeklyFrequency, $allSubjects) {
            $batch->base_subjects()->attach($allSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $kindergartenSubjects = $this->filterSubjectsByTag('Kindergarten', $subjects);
        $this->filterBatchesByLevelNames(['Pre-KG', 'KG-1', 'KG-2'])->each(function ($batch) use ($weeklyFrequency, $kindergartenSubjects) {
            $batch->base_subjects()->attach($kindergartenSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $elementarySubjects = $this->filterSubjectsByTag('Elementary', $subjects);
        $this->filterBatchesByLevelNames(['1', '2', '3', '4', '5', '6', '7', '8'])->each(function ($batch) use ($weeklyFrequency, $elementarySubjects) {
            $batch->base_subjects()->attach($elementarySubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $highSchoolSubjects = $this->filterSubjectsByTag('High School', $subjects);
        $this->filterBatchesByLevelNames(['9', '10', '11', '12'])->each(function ($batch) use ($weeklyFrequency, $highSchoolSubjects) {
            $batch->base_subjects()->attach($highSchoolSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $middleSubjects = $this->filterSubjectsByTag('Middle', $subjects);
        $this->filterBatchesByLevelNames(['7', '8'])->each(function ($batch) use ($weeklyFrequency, $middleSubjects) {
            $batch->base_subjects()->attach($middleSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $lowerHighSchoolSocialSubjects = $this->filterSubjectsByTag('Social', $subjects);
        $this->filterBatchesByLevelNames(['9', '10'])->each(function ($batch) use ($weeklyFrequency, $lowerHighSchoolSocialSubjects) {
            $batch->base_subjects()->attach($lowerHighSchoolSocialSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $lowerHighSchoolNaturalSubjects = $this->filterSubjectsByTag('Natural', $subjects);
        $this->filterBatchesByLevelNames(['9', '10'])->each(function ($batch) use ($weeklyFrequency, $lowerHighSchoolNaturalSubjects) {
            $batch->base_subjects()->attach($lowerHighSchoolNaturalSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $preparatorySocialSubjects = $this->filterSubjectsByTag(['Preparatory', 'Social'], $subjects);
        $this->filterBatchesByLevelNames(['11', '12'], ['A', 'B'])->each(function ($batch) use ($weeklyFrequency, $preparatorySocialSubjects) {
            $batch->base_subjects()->attach($preparatorySocialSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });

        $preparatoryNaturalSubjects = $this->filterSubjectsByTag(['Preparatory', 'Natural'], $subjects);
        $this->filterBatchesByLevelNames(['11', '12'], ['C', 'D'])->each(function ($batch) use ($weeklyFrequency, $preparatoryNaturalSubjects) {
            $batch->base_subjects()->attach($preparatoryNaturalSubjects, [
                'weekly_frequency' => $weeklyFrequency,
            ]);
        });
    }

    private function filterBatchesByLevelNames(array $levelNames, array $sections = null): Builder
    {
        return Batch::with('level')
            ->whereHas('level', function ($query) use ($levelNames) {
                return $query->whereIn('name', $levelNames);
            })->when($sections, function ($query) use ($sections) {
                return $query->whereIn('section', $sections);
            });
    }

    private function allLevelNames(): array
    {
        return Level::all()->pluck('name')->toArray();
    }
}

<?php

namespace Database\Seeders\Simulation;

use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Level;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    private Collection $levels;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolYears = $this->school_years();
        $this->levels = $this->levels();

        $schoolYears->each(function ($schoolYear, $index) {
            $this->levels->each(function ($level) use ($schoolYear, $index) {
                if ($index === 0) {
                    $levelStudents = $this->filterStudentsByAge($level);
                } else {
                    $levelStudents = $this->queryStudentsFromPreviousBatch($level, $schoolYear);
                }

                $this->promoteStudentsToSchoolYear($schoolYear, $level, $levelStudents);
            });
        });
    }

    public function filterStudentsByAge($level): Collection
    {
        $studentsBirthYear = 2023 - (3 + $level->id);

        return Student::with('user', 'batches.batch', 'batches.batch.schoolYear', 'batches.batch.level')->get()->filter(function ($student) use ($studentsBirthYear) {
            return Carbon::createFromDate($student->user->date_of_birth->year)
                    ->between(
                        Carbon::createFromDate($studentsBirthYear - 1),
                        Carbon::createFromDate($studentsBirthYear + 1)
                    ) &&
                $student->batches->count() === 0;
        });
    }

    public function queryStudentsFromPreviousBatch($level, $schoolYear): \Illuminate\Support\Collection
    {
        return BatchStudent::with('student')
            ->whereIn('batch_id', Batch::where([
                'school_year_id' => $schoolYear->id - 1,
                'level_id' => $level->id,
            ])->pluck('id')
            )
            ->get()
            ->pluck('student');
    }

    private function promoteStudentsToSchoolYear($schoolYear, $level, $levelStudents): void
    {
        $batches = [];
        $timestamp = Carbon::createFromDate($schoolYear->start_date)->setMonth(8)->setDay(rand(1, 25));

        // Set up a single Batch's basic data
        $batch = [
            'level_id' => $level->id,
            'school_year_id' => $schoolYear->id,
            'section' => 'A',
            'min_students' => in_array($level->name, ['11', '12']) ? 10 : 17,
            'max_students' => in_array($level->name, ['11', '12']) ? 20 : 40,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];

        // Update the section count according to the number of students
        $sectionCount = $this->getSectionCount($levelStudents->count(), $batch['max_students']);
        if (in_array($level->name, [11, 12])) {
            $sectionCount = 4;
        }

        // Collect and create the Batches for the current Level and update their section
        for ($i = 0; $i < $sectionCount; $i++) {
            $batches[] = $batch;
            $batch['section'] = chr(ord($batch['section']) + 1);
            if ($level->name === '11' || $level->name === '12') {
                $batch['min_students'] = 10;
                $batch['max_students'] = 20;
            }
        }
        $newBatches = collect($batches)->map(function ($batch) {
            return Batch::create($batch);
        });

        // Attach the students to the Batches
        $levelStudents->split($sectionCount)->each(function ($students, $index) use ($newBatches, $timestamp) {
            $newBatches[$index]->base_students()->attach($students->pluck('id'), [
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        });
    }

    private function getSectionCount($levelStudentsCount, $maxStudentCapacity): float
    {
        return ceil($levelStudentsCount / $maxStudentCapacity);
    }

    private function levels(): Collection
    {
        if (Level::count() < 1) {
            $this->call(LevelSeeder::class);
        }

        return Level::all();
    }

    private function school_years(): Collection
    {
        if (SchoolYear::count() < 1) {
            $this->call(SchoolYearSeeder::class);
        }

        return SchoolYear::all();
    }
}

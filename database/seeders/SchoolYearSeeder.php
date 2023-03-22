<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;

        // Create completed semesters for previous years
        for ($year = $previousYear; $year < $currentYear; $year++) {
            $schoolYear = SchoolYear::create([
                'start_date' => Carbon::createFromDate($year, 1, 1),
                'end_date' => Carbon::createFromDate($year, 12, 31),
            ]);

            for ($semesterNumber = 1; $semesterNumber <= 3; $semesterNumber++) {
                Semester::create([
                    'name' => 'Semester '.$semesterNumber,
                    'status' => Semester::STATUS_COMPLETED,
                    'start_date' => Carbon::createFromDate($year, ($semesterNumber - 1) * 4 + 1, 1),
                    'end_date' => Carbon::createFromDate($year, $semesterNumber * 4, 31),
                    'school_year_id' => $schoolYear->id,
                ]);
            }
        }

        // Create completed and active semesters for current year
        $schoolYear = SchoolYear::create([
            'start_date' => Carbon::createFromDate($currentYear, 1, 1),
            'end_date' => null,
        ]);

        Semester::create([
            'name' => 'Semester 1',
            'status' => Semester::STATUS_COMPLETED,
            'start_date' => Carbon::createFromDate($currentYear, 1, 1),
            'end_date' => Carbon::createFromDate($currentYear, 4, 30),
            'school_year_id' => $schoolYear->id,
        ]);

        Semester::create([
            'name' => 'Semester 2',
            'status' => Semester::STATUS_ACTIVE,
            'start_date' => Carbon::createFromDate($currentYear, 5, 1),
            'end_date' => Carbon::createFromDate($currentYear, 8, 31),
            'school_year_id' => $schoolYear->id,
        ]);

        Semester::create([
            'name' => 'Semester 3',
            'status' => Semester::STATUS_UPCOMING,
            'start_date' => Carbon::createFromDate($currentYear, 9, 1),
            'end_date' => Carbon::createFromDate($currentYear, 12, 31),
            'school_year_id' => $schoolYear->id,
        ]);
    }
}

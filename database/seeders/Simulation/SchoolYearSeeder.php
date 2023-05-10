<?php

namespace Database\Seeders\Simulation;

use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = $this->years();

        foreach ($years as $year) {
            $schoolYear = SchoolYear::create([
                'start_date' => $this->start_date($year),
                'end_date' => $year === 2022 ? null : $this->end_date($year),
                'name' => $this->name($year),
            ]);

            $semesters = $schoolYear->semesters()->createMany([
                [
                    'name' => 'Semester 1',
                    'start_date' => $schoolYear->start_date->copy()->addMonths(0),
                    'end_date' => $schoolYear->start_date->copy()->addMonths(5),
                ],
                [
                    'name' => 'Semester 2',
                    'start_date' => $schoolYear->start_date->copy()->addMonths(5),
                    'end_date' => $year === 2022 ? null : $schoolYear->start_date->copy()->addMonths(10),
                ],
            ]);

            $semesters->each(function ($semester) use ($year) {
                $semester->quarters()->createMany([
                    [
                        'name' => 'Quarter 1',
                        'start_date' => $semester->start_date->copy()->addMonths(0),
                        'end_date' => $semester->start_date->copy()->addMonths(2.5),
                    ],
                    [
                        'name' => 'Quarter 2',
                        'start_date' => $semester->start_date->copy()->addMonths(2.5),
                        'end_date' => $semester->name === 'Semester 2' && $year === 2022 ? null : $semester->start_date->copy()->addMonths(5),
                    ],
                ]);
            });
        }
    }

    private function years(): array
    {
        return [
            2016,
            2017,
            2018,
            2019,
            2020,
            2021,
            2022,
        ];
    }

    private function start_date(int $year): Carbon
    {
        return Carbon::createFromDate($year, 9, 11)->next('Monday');
    }

    private function end_date(int $year): Carbon
    {
        return Carbon::createFromDate($year + 1, 6, 15)->next('Monday');
    }

    private function name(int $year): string
    {
        return "School Year $year/".($year + 1);
    }
}

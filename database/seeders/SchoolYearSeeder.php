<?php

namespace Database\Seeders;

use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::times(4, fn ($i) => [
            'start_date' => Carbon::createFromDate(null, 9, 1)->addYears($i - 4),
            'end_date' => $i == 4 ? null : Carbon::createFromDate(null, 9, 1)->addYears($i - 3),
            'name' => 'School Year '.(Carbon::now()->year - 4 + $i).'/'.(Carbon::now()->year - 3 + $i),
        ])->map(function ($schoolYear, $i) {
            $schoolYear = SchoolYear::create($schoolYear);

            return Collection::times(2, fn ($j) => [
                'name' => "Semester $j",
                'start_date' => $schoolYear->start_date->copy()->addMonths(($j - 1) * 5),
                'end_date' => ($i == 3 && $j == 2) ? null : $schoolYear->start_date->copy()->addMonths($j * 5),
                'school_year_id' => $schoolYear->id,
            ])->map(function ($semester, $j) use ($i) {
                $semester = Semester::create($semester);

                return Collection::times(2, fn ($k) => [
                    'name' => "Quarter $k",
                    'start_date' => $semester->start_date->copy()->addMonths(($k - 1) * 2.5),
                    'end_date' => ($i == 3 && $j == 1 && $k == 2) ? null : $semester->start_date->copy()->addMonths($k * 2.5),
                    'semester_id' => $semester->id,
                ])->each(fn ($quarter) => Quarter::create($quarter));
            });
        });
    }
}

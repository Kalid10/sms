<?php

namespace Database\Seeders;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolScheduleSeeder extends Seeder
{
    public function run()
    {
        $schoolYears = SchoolYear::all();

        $schoolYears->each(function ($schoolYear, $key) {
            $numberOfSchedules = $schoolYear->id === 4 ? 15 : 10;

            for ($i = 0; $i < $numberOfSchedules; $i++) {
                $startDate = now()->addDays($i);
                $endDate = $startDate->copy();

                $schedulesPerDay = rand(1, 4);
                for ($j = 0; $j < $schedulesPerDay; $j++) {
                    $title = $startDate->format('l').' Schedule '.($j + 1);
                    SchoolSchedule::factory()->create([
                        'school_year_id' => $schoolYear->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'title' => $title,
                    ]);
                }
            }
        });
    }
}

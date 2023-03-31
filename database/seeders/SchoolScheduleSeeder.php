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

        $schoolYears->each(function ($schoolYear) {
            $count = rand(5, 10);

            SchoolSchedule::factory($count)->create([
                'school_year_id' => $schoolYear->id,
            ]);
        });
    }
}

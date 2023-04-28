<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = -3; $i <= 0; $i++) {
            $startDate = Carbon::createFromDate(null, 9, 1)->addYears($i);
            $endDate = $i == 0 ? null : $startDate->copy()->addMonths(10);
            $name = 'School Year '.($startDate->year).'/'.($endDate ?
                    $endDate->year :
                    $startDate->copy()->addYear()->year
            );

            SchoolYear::factory()->create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'name' => $name,
            ]);
        }
    }
}

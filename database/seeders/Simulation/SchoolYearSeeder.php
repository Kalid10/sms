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
            SchoolYear::factory()->create([
                'start_date' => $this->start_date($year),
                'end_date' => $year === 2022 ? null : $this->end_date($year),
                'name' => $this->name($year),
            ]);
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

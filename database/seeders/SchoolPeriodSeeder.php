<?php

namespace Database\Seeders;

use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SchoolPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSchoolPeriods();
    }

    private function createSchoolPeriods(): void
    {
        $schoolPeriodsData = [
            [
                'no_of_periods' => 8,
                'minutes_per_period' => 40,
                'start_time' => '02:30',
                'level_category_ids' => [1, 3],
                'custom_periods' => [
                    [
                        'name' => 'Homeroom Period',
                        'duration' => 10,
                        'before_period' => 1,
                    ],
                    [
                        'name' => 'Recess',
                        'duration' => 20,
                        'before_period' => 4,
                    ],
                    [
                        'name' => 'Lunch Break',
                        'duration' => 40,
                        'before_period' => 6,
                    ],
                ],
            ],
            [
                'no_of_periods' => 8,
                'minutes_per_period' => 40,
                'start_time' => '02:30',
                'level_category_ids' => [2],
                'custom_periods' => [
                    [
                        'name' => 'Homeroom Period',
                        'duration' => 10,
                        'before_period' => 1,
                    ],
                    [
                        'name' => 'Recess',
                        'duration' => 20,
                        'before_period' => 3,
                    ],
                    [
                        'name' => 'Lunch Break',
                        'duration' => 40,
                        'before_period' => 5,
                    ],
                ],
            ],
        ];

        foreach ($schoolPeriodsData as $data) {
            $activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id; // Replace with the active school year ID
            $start_time = Carbon::parse($data['start_time']);
            $custom_periods = collect($data['custom_periods'])->keyBy('before_period');

            foreach ($data['level_category_ids'] as $level_category_id) {
                $this->createSchoolPeriodsForLevelCategory(
                    $start_time->copy(),
                    $data['minutes_per_period'],
                    $data['no_of_periods'],
                    $custom_periods,
                    $activeSchoolYearId,
                    $level_category_id
                );
            }
        }
    }

    private function createSchoolPeriodsForLevelCategory($start_time, $minutes_per_period, $no_of_periods, $custom_periods, $activeSchoolYearId, $level_category_id): void
    {
        $periods = [];

        for ($i = 1; $i <= $no_of_periods; $i++) {
            if ($custom_periods->has($i)) {
                $custom_period = $custom_periods->get($i);

                $periods[] = [
                    'name' => $custom_period['name'],
                    'start_time' => $start_time->format('H:i'),
                    'duration' => $custom_period['duration'],
                    'is_custom' => true,
                    'school_year_id' => $activeSchoolYearId,
                    'level_category_id' => $level_category_id,
                ];
                $start_time->addMinutes($custom_period['duration']);
            }

            $periods[] = [
                'name' => $i,
                'start_time' => $start_time->format('H:i'),
                'duration' => $minutes_per_period,
                'is_custom' => false,
                'school_year_id' => $activeSchoolYearId,
                'level_category_id' => $level_category_id,
            ];
            $start_time->addMinutes($minutes_per_period);
        }

        SchoolPeriod::insert($periods);
    }
}

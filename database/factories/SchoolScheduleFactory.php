<?php

namespace Database\Factories;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolSchedule>
 */
class SchoolScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $schoolYear = SchoolYear::inRandomOrder()->first();
        $startDate = Carbon::parse($schoolYear->start_date)->addDays(rand(1, 90));
        $endDate = Carbon::parse($schoolYear->end_date)->subDays(rand(1, 90));
        $type = fake()->randomElement(['holiday', 'event']);

        return [
            'title' => fake()->sentence(5),
            'body' => fake()->sentence,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $type,
            'school_year_id' => $schoolYear->id,
        ];
    }
}

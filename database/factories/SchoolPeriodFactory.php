<?php

namespace Database\Factories;

use App\Models\LevelCategory;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolPeriod>
 */
class SchoolPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $schoolYear = SchoolYear::whereNull('end_date')->first() ?? SchoolYear::factory()->create();
        $levelCategory = LevelCategory::all()->random() ?? LevelCategory::factory()->create();

        return [
            'name' => $this->faker->randomDigit,
            'start_time' => $this->faker->time('H:i'),
            'duration' => $this->faker->numberBetween(1, 120),
            'is_custom' => false,
            'school_year_id' => $schoolYear->id,
            'level_category_id' => $levelCategory->id,
        ];
    }
}

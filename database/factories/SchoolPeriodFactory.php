<?php

namespace Database\Factories;

use App\Models\LevelCategory;
use App\Models\SchoolPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolPeriodFactory extends Factory
{
    protected $model = SchoolPeriod::class;

    public function definition()
    {
        $start_time = $this->faker->time('H:i');

        return [
            'name' => $this->faker->unique()->numberBetween(1, 50),
            'start_time' => $start_time,
            'duration' => $this->faker->numberBetween(40, 60),
            'is_custom' => $this->faker->boolean(20),
            'level_category_id' => LevelCategory::factory(),
        ];
    }
}

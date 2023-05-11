<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\LevelCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'level_category_id' => LevelCategory::inRandomOrder()->first()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Quarter;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Quarter>
 */
class QuarterFactory extends Factory
{
    protected $model = Quarter::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'semester_id' => Semester::factory()->create([
                'status' => Semester::STATUS_ACTIVE,
            ]),
        ];
    }
}

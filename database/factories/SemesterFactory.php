<?php

namespace Database\Factories;

use App\Models\SchoolYear;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'status' => $this->faker->word,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'school_year_id' => SchoolYear::factory()->create([
                'end_date' => Carbon::now(),
            ]),
        ];
    }
}

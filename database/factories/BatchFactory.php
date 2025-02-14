<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Batch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $schoolYear = SchoolYear::whereNull('end_date')->first() ?? SchoolYear::factory()->create();

        return [
            'level_id' => $this->faker->randomElement(Level::all()->pluck('id')),
            'school_year_id' => $schoolYear->id,
            'section' => $this->faker->regexify('[A-Z]{1}'),
            'min_students' => $this->faker->numberBetween(30, 50),
            'max_students' => $this->faker->numberBetween(51, 90),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolYear;
use Carbon\Carbon;
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
        return [
            'level_id' => Level::all()->random()->id,
            'school_year_id' => SchoolYear::create([
                'start_date' => Carbon::now()->subYear(),
                'end_date' => null,
            ])->id,
            'section' => $this->faker->unique()->randomLetter,
        ];
    }
}

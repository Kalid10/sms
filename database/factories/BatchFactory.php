<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

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
        $level = Level::inRandomOrder()->first() ?? Level::factory()->create();
        $schoolYear = SchoolYear::whereNull('end_date')->first() ?? SchoolYear::factory()->create();

        Log::info(SchoolYear::whereNull('end_date')->first());

        return [
            'level_id' => $level->id,
            'school_year_id' => $schoolYear->id,
            'section' => $this->faker->regexify('[A-Z]{1}'),
        ];
    }
}

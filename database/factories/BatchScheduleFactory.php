<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\SchoolPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BatchSchedule>
 */
class BatchScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $batch = Batch::factory()->create();
        $schoolPeriod = SchoolPeriod::factory()->create();

        return [
            'batch_id' => $batch->id,
            'school_period_id' => $schoolPeriod->id,
            'day_of_week' => 1,
            'batch_subject_id' => fake()->randomElement($batch->subjects->pluck('id')->toArray()),
        ];
    }
}

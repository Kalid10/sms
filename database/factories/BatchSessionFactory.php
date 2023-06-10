<?php

namespace Database\Factories;

use App\Models\BatchSchedule;
use App\Models\BatchSession;
use App\Models\Quarter;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BatchSession>
 */
class BatchSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_schedule_id' => BatchSchedule::factory()->create()->id,
            'teacher_id' => Teacher::factory()->create()->id,
            'status' => BatchSession::STATUS_IN_PROGRESS,
            'date' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'quarter_id' => Quarter::factory()->create()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BatchSubject>
 */
class BatchSubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_id' => Batch::factory()->create()->id,
            'subject_id' => Subject::factory()->create()->id,
            'teacher_id' => Teacher::factory()->create()->id,
        ];
    }
}

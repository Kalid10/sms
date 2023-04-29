<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\TeacherFeedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherFeedback>
 */
class TeacherFeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teacher = Teacher::inRandomOrder()->first();
        $author = User::where('type', '!=', User::TYPE_TEACHER)->inRandomOrder()->first();

        return [
            'teacher_id' => $teacher->id,
            'author_id' => $author->id,
            'feedback' => fake()->realText(200),
        ];
    }
}

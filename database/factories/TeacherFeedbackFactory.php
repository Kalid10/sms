<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\TeacherFeedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

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

        Log::info("this is teacher id: $teacher->id");
        Log::info("this is author id: $author->id");

        return [
            'teacher_id' => $teacher->id,
            'author_id' => $author->id,
            'feedback' => fake()->realText(200),
        ];
    }
}

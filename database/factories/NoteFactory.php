<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => Teacher::inRandomOrder()->first()->user->id,
            'entity_id' => Student::inRandomOrder()->first()->user->id,
            'batch_session_id' => User::inRandomOrder()->first(),
        ];
    }
}

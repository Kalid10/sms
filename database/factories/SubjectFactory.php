<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'bio' => 'Biology',
            'chem' => 'Chemistry',
            'geo' => 'geography',
            'eng' => 'English',
            'hist' => 'History',
            'sci' => 'Science',
            'P.E' => 'Physical Education',
            'phy' => 'Physics',
            'social' => 'Social Studies',
            'alg' => 'Algebra',
        ];

        $random = fake()->unique()->randomElement(['bio', 'chem', 'geo', 'eng', 'hist', 'sci', 'P.E', 'phy', 'social', 'alg']);

        return [
            'full_name' => $subjects[$random],
            'short_name' => $random,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\LevelCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LevelCategory>
 */
class LevelCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = collect([
            ['name' => 'Preparatory'],
            ['name' => 'HighSchool'],
            ['name' => 'MiddleSchool'],
            ['name' => 'PrimarySchool'],
        ]);

        return [
            'name' => $categories->each(function ($category) {
                LevelCategory::create($category);
            })->random()->get('name'),
        ];
    }
}

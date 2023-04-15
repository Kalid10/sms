<?php

namespace Database\Factories;

use App\Models\LevelCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelCategoryFactory extends Factory
{
    protected $model = LevelCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}

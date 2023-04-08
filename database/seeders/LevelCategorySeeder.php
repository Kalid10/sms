<?php

namespace Database\Seeders;

use App\Models\LevelCategory;
use Illuminate\Database\Seeder;

class LevelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Preparatory'],
            ['name' => 'HighSchool'],
            ['name' => 'MiddleSchool'],
            ['name' => 'PrimarySchool'],
        ]);

        $categories->each(function ($category) {
            LevelCategory::create($category);
        });
    }
}

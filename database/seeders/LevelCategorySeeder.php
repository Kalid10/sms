<?php

namespace Database\Seeders;

use App\Models\LevelCategory;
use Illuminate\Database\Seeder;

class LevelCategorySeeder extends Seeder
{
    public function run()
    {
        $levelCategories = ['Preparatory', 'HighSchool', 'MiddleSchool', 'PrimarySchool'];

        foreach ($levelCategories as $levelCategory) {
            LevelCategory::create(['name' => $levelCategory]);
        }
    }
}

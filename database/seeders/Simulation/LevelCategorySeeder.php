<?php

namespace Database\Seeders\Simulation;

use App\Models\LevelCategory;
use Illuminate\Database\Seeder;

class LevelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelCategories = $this->levelCategories();

        foreach ($levelCategories as $levelCategory) {
            LevelCategory::create([
                'name' => $levelCategory,
            ]);
        }
    }

    private function levelCategories(): array
    {
        return [
            'Kindergarten',
            'Primary',
            'Secondary',
        ];
    }
}

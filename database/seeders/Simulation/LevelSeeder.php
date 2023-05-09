<?php

namespace Database\Seeders\Simulation;

use App\Models\Level;
use App\Models\LevelCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelCategories = $this->levelCategories();
        $kindergarten = $levelCategories[0];
        $primary = $levelCategories[1];
        $secondary = $levelCategories[2];

        for ($i = 1; $i <= 3; $i++) {
            Level::factory()->create([
                'name' => ($i === 1 ? 'Pre-' : '').'KG'.($i > 1 ? '-'.($i - 1) : ''),
                'level_category_id' => $kindergarten->id,
            ]);
        }

        for ($i = 1; $i <= 12; $i++) {
            Level::factory()->create([
                'name' => $i,
                'level_category_id' => $i <= 8 ? $primary->id : $secondary->id,
            ]);
        }
    }

    private function levelCategories(): Collection
    {
        if (LevelCategory::count() < 1) {
            $this->call(LevelCategorySeeder::class);
        }

        return LevelCategory::all();
    }
}

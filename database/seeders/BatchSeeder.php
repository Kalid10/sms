<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = Level::all();
        $currentSchoolYear = SchoolYear::whereNull('end_date')->first();
        $previousSchoolYear = SchoolYear::whereNotNull('end_date')->first();

        $this->seedBatches($levels, $currentSchoolYear);
        $this->seedBatches($levels, $previousSchoolYear);
    }

    private function seedBatches($levels, $schoolYear)
    {
        $sections = range('A', 'Z');
        $maxSections = 6;

        foreach ($levels as $level) {
            $sectionCount = rand(1, $maxSections);
            for ($i = 0; $i < $sectionCount; $i++) {
                Batch::factory()->create([
                    'level_id' => $level->id,
                    'school_year_id' => $schoolYear->id,
                    'section' => $sections[$i],
                    'min_students' => fake()->numberBetween(5, 10),
                    'max_students' => fake()->numberBetween(20, 30),
                ]);
            }
        }
    }
}

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
     */
    public function run(): void
    {
        // Get all levels and school years
        $levels = Level::all();
        $schoolYears = SchoolYear::all();

        // Create batches for each combination of level and school year
        foreach ($levels as $level) {
            foreach ($schoolYears as $schoolYear) {
                Batch::create([
                    'level_id' => $level->id,
                    'school_year_id' => $schoolYear->id,
                    'section' => 'A', // Replace with your desired section
                ]);
            }
        }
    }
}

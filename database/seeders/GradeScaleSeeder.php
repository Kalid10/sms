<?php

namespace Database\Seeders;

use App\Models\GradeScale;
use Illuminate\Database\Seeder;

class GradeScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->gradeScales() as $gradeScale) {
            GradeScale::factory()->create($gradeScale);
        }
    }

    private function gradeScales(): array
    {
        return [
            [
                'label' => 'A',
                'minimum_score' => 90,
                'state' => 'pass',
                'description' => 'Excellent, Exceeds Expectations',
            ],
            [
                'label' => 'B',
                'minimum_score' => 80,
                'state' => 'pass',
                'description' => 'Good, Meets Expectations',
            ],
            [
                'label' => 'C',
                'minimum_score' => 70,
                'state' => 'pass',
                'description' => 'Satisfactory, Meets Expectations',
            ],
            [
                'label' => 'D',
                'minimum_score' => 60,
                'state' => 'pass',
                'description' => 'Poor, Below Expectations',
            ],
            [
                'label' => 'F',
                'minimum_score' => 0,
                'state' => 'fail',
                'description' => 'Failure, Does Not Meet Expectations',
            ],
        ];
    }
}

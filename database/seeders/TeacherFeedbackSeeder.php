<?php

namespace Database\Seeders;

use App\Models\TeacherFeedback;
use Illuminate\Database\Seeder;

class TeacherFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeacherFeedback::factory()->count(10)->create([
            'teacher_id' => 1,
        ]);

        TeacherFeedback::factory()->count(50)->create();
    }
}

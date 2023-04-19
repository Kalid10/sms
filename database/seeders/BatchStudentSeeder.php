<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Student;
use Illuminate\Database\Seeder;

class BatchStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add students to batch_students table
        $batch = Batch::find(1);
        $students = Student::all();

        foreach ($students as $student) {
            BatchStudent::create([
                'student_id' => $student->id,
                'batch_id' => $batch->id,
            ]);
        }
    }
}

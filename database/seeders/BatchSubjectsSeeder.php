<?php

namespace Database\Seeders;

use App\Models\BatchSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class BatchSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve the current school year
        $schoolYear = SchoolYear::whereNull('end_date')->first();

        // Retrieve the batches for the current school year
        $batches = $schoolYear->batches;

        // Retrieve the subjects and teachers you want to assign to the batches
        $subjects = Subject::all();

        // Assign unique teachers for each subject
        $teachers = Teacher::all()->unique('id');

        $teacherIndex = 0;

        // Assign the subjects to the batches
        foreach ($batches as $batch) {
            foreach ($subjects as $subject) {
                // Get the current teacher
                $teacher = $teachers[$teacherIndex];

                // Assign the subject to the current teacher
                BatchSubject::create([
                    'batch_id' => $batch->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'weekly_frequency' => 4,
                ]);

                // Move to the next teacher
                $teacherIndex++;

                // Reset the teacher index if all teachers have been assigned
                if ($teacherIndex >= $teachers->count()) {
                    $teacherIndex = 0;
                }
            }
        }
    }
}

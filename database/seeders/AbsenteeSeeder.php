<?php

namespace Database\Seeders;

use App\Models\Absentee;
use App\Models\BatchSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class AbsenteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO:: This create to many absentee records, update it to optimize it and make it realistic
        // Retrieve all BatchSessions
        $batchSessions = BatchSession::whereNotNull('teacher_id')->with('batchSubject.students')->get();

        // Select a few students who are more likely to be absent more often
        $frequentlyAbsentStudents = User::whereHas('student')->inRandomOrder()->take(10)->pluck('id');

        // For each BatchSession
        foreach ($batchSessions as $batchSession) {
            // Retrieve the students associated with the current BatchSession through the BatchSubject relation
            $students = $batchSession->batchSubject->students;

            // Get approximately one fourth of the students count
            $absentCount = intval($students->count() / 4);

            // Randomly select one fourth of the students to be marked as absent
            $absentStudents = $students->random($absentCount);

            // For each selected student, create an absentee record for the current BatchSession
            foreach ($absentStudents as $student) {
                // Check if this student is more likely to be absent
                $isFrequentlyAbsent = $frequentlyAbsentStudents->contains($student->user_id);

                // Skip this student if they are not frequently absent
                if (! $isFrequentlyAbsent && rand(0, 1) === 1) {
                    continue;
                }

                // Create an absentee record
                Absentee::create([
                    'batch_session_id' => $batchSession->id,
                    'user_id' => $student->user_id,
                    'reason' => 'Random Reason', // You might want to replace this with a realistic reason or a logic to generate it
                ]);
            }
        }
    }
}

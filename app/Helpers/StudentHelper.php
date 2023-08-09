<?php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Flag;
use App\Models\Quarter;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentHelper
{
    public static function assignStudentToBatch($studentId, $levelId): bool
    {
        // Check if the student is already assigned to a batch
        $alreadyAssigned = BatchStudent::where('student_id', $studentId)->exists();

        if ($alreadyAssigned) {
            return true;
        }

        // Find all the batches in the student's level
        $batches = Batch::where('level_id', $levelId)->get();

        // Initialize variables to store the minimum number of students and the target batch
        $minStudentCount = PHP_INT_MAX;
        $targetBatch = null;

        // Loop through the batches and find the batch with the minimum number of students
        foreach ($batches as $batch) {
            $studentCount = BatchStudent::where('batch_id', $batch->id)->count();

            if ($studentCount < $minStudentCount && $studentCount < $batch->max_students) {
                $minStudentCount = $studentCount;
                $targetBatch = $batch;
            }
        }

        // If a target batch is found, assign the student to the batch
        if ($targetBatch) {
            DB::table('batch_students')->updateOrInsert(
                ['batch_id' => $targetBatch->id, 'student_id' => $studentId],
                [
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );

            return true;
        }

        // If no suitable batch is found, return false
        return false;
    }

    public static function generateUsername($name): string
    {
        // Combine the first and last names and convert to lowercase
        $username = Str::lower($name);

        // Replace any spaces or special characters with underscores
        $username = preg_replace('/[^a-zA-Z0-9]+/', '-', $username);

        // Add a random number to the end to make it unique
        $username .= rand(100, 999);

        // Check if the username already exists and add a number if necessary
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username .= $i++;
        }

        return $username;
    }

    public static function flagStudent($flaggable_id, $type, $description, $flagged_by, $batch_subject_id, $expires_at, $is_homeroom = null): void
    {
        $flag = Flag::where('flaggable_id', $flaggable_id)
            ->where('flaggable_type', Student::class)
            ->where('batch_subject_id', $batch_subject_id)
            ->first();

        // If a flag exists, update the type and description
        if ($flag) {
            // Update the flag
            $flag->update([
                'type' => (array) $type,
                'description' => $description,
                'flagged_by' => $flagged_by,
                'expires_at' => date('Y-m-d H:i:s', strtotime($expires_at)),
                'quarter_id' => Quarter::getActiveQuarter()->id,
            ]);
        } else {
            // If the flag doesn't exist, create a new one
            Flag::create([
                'flaggable_id' => $flaggable_id,
                'flaggable_type' => Student::class,
                'type' => (array) $type,
                'description' => $description,
                'flagged_by' => $flagged_by,
                'batch_subject_id' => $batch_subject_id,
                'expires_at' => date('Y-m-d H:i:s', strtotime($expires_at)),
                'quarter_id' => Quarter::getActiveQuarter()->id,
                'is_homeroom' => $is_homeroom,
            ]);
        }
    }
}

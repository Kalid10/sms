<?php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\User;
use Illuminate\Support\Str;

class StudentHelper
{
    public static function assignStudentToBatch($studentId, $levelId): bool
    {
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
            BatchStudent::create([
                'batch_id' => $targetBatch->id,
                'student_id' => $studentId,
            ]);

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
}

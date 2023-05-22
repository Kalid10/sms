<?php

namespace App\Observers;

use App\Models\Assessment;
use App\Models\StudentAssessment;

class AssessmentObserver
{
    public function updated(Assessment $assessment): void
    {
        if ($assessment->isDirty('status') && $assessment->status === Assessment::STATUS_PUBLISHED) {
            $this->createStudentAssessments($assessment);
        }
    }

    public function created(Assessment $assessment): void
    {
        if ($assessment->status === Assessment::STATUS_PUBLISHED) {
            $this->createStudentAssessments($assessment);
        }
    }

    private function createStudentAssessments(Assessment $assessment): void
    {
        if ($assessment->status !== Assessment::STATUS_PUBLISHED) {
            return;
        }

        // Fetch all students in the batch and create student assessments for each student
        $assessment->batchSubject->students->each(function ($student) use ($assessment) {
            StudentAssessment::create([
                'assessment_id' => $assessment->id,
                'student_id' => $student->id,
            ]);
        });
    }
}

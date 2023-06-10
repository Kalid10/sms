<?php

namespace App\Observers;

use App\Models\Assessment;
use App\Models\StudentAssessment;

class AssessmentObserver
{
    public function updated(Assessment $assessment): void
    {
        if ($assessment->isDirty('status') && $assessment->status === Assessment::STATUS_SCHEDULED
            || $assessment->status === Assessment::STATUS_PUBLISHED) {
            $this->createStudentAssessments($assessment);
        }

        if ($assessment->isDirty('status') && $assessment->status === Assessment::STATUS_DRAFT) {
            $studentAssessments = StudentAssessment::where('assessment_id', $assessment->id)->whereNotNull('point')->get();
            if ($studentAssessments->count() > 0) {
                return;
            }
            StudentAssessment::where('assessment_id', $assessment->id)->delete();
        }
    }

    public function created(Assessment $assessment): void
    {
        if ($assessment->status === Assessment::STATUS_SCHEDULED || $assessment->status === Assessment::STATUS_PUBLISHED) {
            $this->createStudentAssessments($assessment);
        }
    }

    private function createStudentAssessments(Assessment $assessment): void
    {
        if ($assessment->status !== Assessment::STATUS_PUBLISHED && $assessment->status !== Assessment::STATUS_SCHEDULED) {
            return;
        }

        // Fetch all students in the batch and create student assessments for each student
        $assessment->batchSubject->students->each(function ($student) use ($assessment) {
            // Check if there is a soft-deleted record for the student assessment
            $studentAssessment = StudentAssessment::withTrashed()->where([
                'assessment_id' => $assessment->id,
                'student_id' => $student->id,
            ])->first();

            // If there is a soft-deleted record, restore it
            if ($studentAssessment && $studentAssessment->trashed()) {
                $studentAssessment->restore();
            } // If there is no record, create a new one
            elseif (! $studentAssessment) {
                StudentAssessment::create([
                    'assessment_id' => $assessment->id,
                    'student_id' => $student->id,
                ]);
            }
        });
    }
}

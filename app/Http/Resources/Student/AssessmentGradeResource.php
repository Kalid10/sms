<?php

namespace App\Http\Resources\Student;

use App\Models\GradeScale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'profile_image' => $this->user->profile_image,
            'assessments' => $this->assessments?->map(function ($assessment) {
                return [
                    'assessment_id' => $assessment->id,
                    'assessment_title' => $assessment->assessment->title,
                    'maximum_point' => $assessment->assessment->maximum_point,
                    'point' => $assessment->point,
                    'grade' => $assessment->point ?
                        GradeScale::get($assessment->point, $assessment->assessment->maximum_point, $assessment->assessment->batchSubject->batch->schoolYear->id) :
                        null,
                    'comment' => $assessment->comment,
                    'grade_status' => $assessment->status,
                    'status' => $assessment->assessment->status,
                    'due_date' => $assessment->assessment->due_date,
                    'assessment_type' => [
                        'id' => $assessment->assessment->assessmentType->id,
                        'name' => $assessment->assessment->assessmentType->name,
                    ],
                    'subject_full_name' => $assessment->assessment->batchSubject->subject->full_name,
                    'subject_short_name' => $assessment->assessment->batchSubject->subject->short_name,
                    'teacher_id' => $assessment->assessment->batchSubject->teacher?->id,
                    'teacher_name' => $assessment->assessment->batchSubject->teacher?->user?->name,
                    'teacher_profile_image' => $assessment->assessment->batchSubject->teacher?->user?->profile_image,
                ];
            }),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentAssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'assessments' => $this->assessments->map(function ($assessment) {
                return [
                    'title' => $assessment->assessment->title,
                    'description' => $assessment->assessment->description,
                    'maximum_point' => $assessment->assessment->maximum_point,
                    'status' => $assessment->assessment->status,
                    'due_date' => $assessment->assessment->due_date,
                    'assessment_type' => $assessment->assessment->assessmentType->name,
                    'percentage' => $assessment->assessment->assessmentType->percentage,
                    'subject_full_name' => $assessment->assessment->batchSubject->subject->full_name,
                    'subject_short_name' => $assessment->assessment->batchSubject->subject->short_name,
                    'subject_category' => $assessment->assessment->batchSubject->subject->category,
                    'subject_tags' => $assessment->assessment->batchSubject->subject->tags,
                ];
            }),
        ];
    }
}

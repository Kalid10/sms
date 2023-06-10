<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'assessments' => $this->currentBatch[0]->subjects->pluck('assessments')->flatten()->map(function ($assessment) {
                return [
                    'title' => $assessment->title,
                    'description' => $assessment->description,
                    'maximum_point' => $assessment->maximum_point,
                    'status' => $assessment->status,
                    'due_date' => $assessment->due_date,
                    'assessment_type' => $assessment->assessmentType->name,
                    'percentage' => $assessment->assessmentType->percentage,
                    'subject_full_name' => $assessment->batchSubject->subject->full_name,
                    'subject_short_name' => $assessment->batchSubject->subject->short_name,
                    'subject_category' => $assessment->batchSubject->subject->category,
                    'subject_tags' => $assessment->batchSubject->subject->tags,
                ];
            }),
        ];
    }
}

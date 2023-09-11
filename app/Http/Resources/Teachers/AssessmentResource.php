<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'title' => $this->title,
            'long_title' => $this->long_title,
            'description' => $this->description,
            'maximum_point' => $this->maximum_point,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'assessment_type_id' => $this->assessment_type_id,
            'batch_subject_id' => $this->batch_subject_id,
            'quarter_id' => $this->quarter_id,
            'batch_id' => $this->batchSubject->batch_id,
            'subject_id' => $this->batchSubject->subject_id,
            'level_id' => $this->batchSubject->batch->level_id,
            'subject' => [
                'full_name' => $this->batchSubject->subject->full_name,
                'short_name' => $this->batchSubject->subject->short_name,
            ],
            'batch' => [
                'name' => $this->parseBatchName(
                    $this->batchSubject->batch->level->name,
                    $this->batchSubject->batch->section
                ),
                'level' => $this->batchSubject->batch->level->name,
                'section' => $this->batchSubject->batch->section,
                'school_year' => $this->batchSubject->batch->schoolYear->name,
            ],
            'quarter' => $this->quarter->name,
            'semester' => $this->quarter->semester->name,
        ];
    }

    private function parseLevel($level): string
    {
        return is_numeric($level) ? 'Grade '.$level : $level;
    }

    private function parseBatchName($level, $section): string
    {
        return $this->parseLevel($level).' '.$section;
    }
}

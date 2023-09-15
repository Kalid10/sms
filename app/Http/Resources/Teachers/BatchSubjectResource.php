<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchSubjectResource extends JsonResource
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
            'subject_id' => $this->subject->id,
            'batch_id' => $this->batch->id,
            'subject' => [
                'id' => $this->subject->id,
                'full_name' => $this->subject->full_name,
                'short_name' => $this->subject->short_name,
            ],
            'batch' => new BatchResource($this->batch),
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

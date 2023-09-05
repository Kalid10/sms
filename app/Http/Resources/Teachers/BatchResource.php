<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            'batch_id' => $this->batch->id,
            'batch' => [
                'id' => $this->batch->id,
                'name' => $this->parseBatchName(
                    $this->batch->level->name,
                    $this->batch->section
                ),
                'level' => $this->batch->level->name,
                'section' => $this->batch->section,
                'school_year' => $this->batch->schoolYear->name,
            ],
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

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
            'name' => $this->parseBatchName(
                $this->level->name,
                $this->section
            ),
            'level' => $this->level->name,
            'section' => $this->section,
            'school_year' => $this->schoolYear->name,
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

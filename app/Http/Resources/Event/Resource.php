<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ?? null,
            'title' => $this->title,
            'description' => $this->body ?? $this->description,
            'date_on' => $this->start_date ?? $this->due_date,
            'assessment_period_name' => $this->assessment_period_time->name ?? null,
            'assessment_period_start_time' => $this->assessment_period_time->start_time ?? null,
            'assessment_period_duration' => $this->assessment_period_time->duration ?? null,
            'schedule_type' => $this->type ?? null,
            'event_type' => $this->getEventType(),
            'tags' => $this->tags ?? null,
        ];
    }

    private function getEventType(): string
    {
        return $this->type === null ? 'assessment' : 'school_schedule';
    }
}

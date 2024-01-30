<?php

namespace App\Http\Resources\Teachers;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\SchoolSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->isAssessment()) {
            return [
                'assessment' => new AssessmentResource($this),
                'type' => 'Assessment',
            ];
        } elseif ($this->isAnnouncement()) {
            return [
                'announcement' => [
                    'id' => $this->id,
                    'title' => $this->title,
                    'body' => $this->body,
                    'expires_on' => $this->expires_on,
                    'author' => [
                        'user_id' => $this->author->id,
                        'name' => $this->author->user->name,
                        'position' => $this->author->position,
                        'phone_number' => $this->author->user->phone_number,
                    ],
                ],
                'type' => 'Announcement',
            ];
        } elseif ($this->isSchoolSchedule()) {
            return [
                'school_schedule' => [
                    'title' => $this->title,
                    'body' => $this->body,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'schedule_type' => $this->type,
                    'tags' => $this->tags,
                ],
                'type' => 'School Schedule',
            ];
        } else {
            return [
                'type' => 'Unknown Event Type',
            ];
        }
    }

    private function isAssessment(): bool
    {
        return $this->resource instanceof Assessment;
    }

    private function isAnnouncement(): bool
    {
        return $this->resource instanceof Announcement;
    }

    private function isSchoolSchedule(): bool
    {
        return $this->resource instanceof SchoolSchedule;
    }
}

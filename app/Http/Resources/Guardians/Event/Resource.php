<?php

namespace App\Http\Resources\Guardians\Event;

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
        if ($this->isAssessment()) {
            return [
                'student_id' => $this->student->id,
                'student_name' => $this->student->user->name,
                'student_profile_image' => $this->student->user->profile_image,
                'title' => $this->assessment->title,
                'long_title' => $this->assessment->long_title,
                'description' => $this->assessment->description,
                'date_on' => $this->assessment->due_date,
                'type' => $this->assessment->assessmentType->name,
            ];
        }

        return [
            'student_id' => null,
            'student_name' => null,
            'title' => $this->title,
            'long_title' => null,
            'description' => $this->body,
            'date_on' => $this->start_date,
            'type' => 'Calendar Event',
        ];
    }

    private function isAssessment(): bool
    {
        return (bool) $this->assessment;
    }
}

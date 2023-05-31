<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentSchedulesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $schedules = $this->batch['schedule'] ?? [];

        return [
            'student_id' => $this->student_id,
            'schedules' => collect($schedules)->map(function ($schedule) {
                return [
                    'day_of_week' => $schedule['day_of_week'] ?? null,
                    'subject' => $schedule->batchSubject->subject->full_name ?? null,
                    'teacher' => $schedule->batchSubject->teacher->user->name ?? null,
                ];
            })->toArray(),
        ];
    }
}

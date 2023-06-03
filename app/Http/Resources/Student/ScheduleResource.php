<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $schedules = $this->currentBatch ? $this->currentBatch[0]->schedule : null;

        return [
            'student_id' => $this->id,
            'student_name' => $this->user->name,
            'schedules' => collect($schedules)->map(function ($schedule) {
                return [
                    'day_of_week' => $schedule['day_of_week'],
                    'subject_full_name' => $schedule->batchSubject->subject->full_name,
                    'subject_short_name' => $schedule->batchSubject->subject->short_name,
                    'subject_category' => $schedule->batchSubject->subject->category,
                    'subject_tags' => $schedule->batchSubject->subject->tags,
                    'teacher' => $schedule->batchSubject->teacher->user->name,
                ];
            })->toArray(),
        ];
    }
}

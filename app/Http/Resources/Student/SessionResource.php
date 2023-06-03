<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sessions = $this->currentBatch ? $this->currentBatch[0]->sessions : null;

        return [
            'student_id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'sessions' => collect($sessions)->map(function ($session) {
                return [
                    'session_id' => $session->id,
                    'session_status' => $session->status,
                    'session_date' => $session->date,
                    'session_subject' => $session->batchSubject->subject->full_name,
                    'session_teacher' => $session->teacher->user->name,
                    'session_teacher_email' => $session->teacher->user->email,
                    'period' => $session->schoolPeriod->name,
                    'period_start_time' => $session->schoolPeriod->start_time,
                    'period_duration' => $session->schoolPeriod->duration,
                    'lesson_plan_topic' => $session->lessonPlan?->topic,
                    'lesson_plan_description' => $session->lessonPlan?->description,
                    'schedule_day' => $session->batchSchedule->day_of_week,
                    'absentee' => $session->attendances->map(function ($attendance) {
                        return [
                            'reason' => $attendance->reason,
                        ];
                    }),
                ];
            })->toArray(),
        ];
    }
}

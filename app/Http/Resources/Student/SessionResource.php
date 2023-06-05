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
        $sessions = $this->currentBatch ? $this->currentBatch[0]->weeklySessions : null;

        return [
            'student_id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'weekly_sessions' => collect($sessions)->map(function ($weeklySession) {
                return [
                    'session_id' => $weeklySession->id,
                    'session_status' => $weeklySession->status,
                    'session_date' => $weeklySession->date,
                    'session_subject' => $weeklySession->batchSubject->subject->full_name,
                    'session_teacher' => $weeklySession->teacher->user->name,
                    'session_teacher_email' => $weeklySession->teacher->user->email,
                    'period' => $weeklySession->schoolPeriod->name,
                    'period_start_time' => $weeklySession->schoolPeriod->start_time,
                    'period_duration' => $weeklySession->schoolPeriod->duration,
                    'lesson_plan_topic' => $weeklySession->lessonPlan?->topic,
                    'lesson_plan_description' => $weeklySession->lessonPlan?->description,
                    'schedule_day' => $weeklySession->batchSchedule->day_of_week,
                    'absentee' => $weeklySession->attendances->map(function ($attendance) {
                        return [
                            'reason' => $attendance->reason,
                        ];
                    }),
                ];
            })->toArray(),
        ];
    }
}

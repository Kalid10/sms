<?php

namespace App\Http\Resources\Guardians\Student;

use Carbon\Carbon;
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
            'batch_subject_id' => $this->id,
            'batch' => [
                'batch_id' => $this->batch->id,
                'level_id' => $this->batch->level->id,
                'level' => $this->batch->level->name,
                'section' => $this->batch->section,
            ],
            'subject' => [
                'subject_id' => $this->subject->id,
                'name' => $this->subject->full_name,
                'short_name' => $this->subject->short_name,
            ],
            'teacher' => [
                'teacher_id' => $this->teacher->id,
                'name' => $this->teacher->user->name,
                'profile_image' => $this->teacher->user->profile_image,
            ],
            'schedules' => $this->schedule->map(function ($schedule) {
                return [
                    'schedule_id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'period' => $schedule->schoolPeriod->name,
                    'start_time' => $schedule->schoolPeriod->start_time,
                    'duration' => $schedule->schoolPeriod->duration,
                    'end_time' => Carbon::parse($schedule->schoolPeriod->start_time)
                        ->addMinutes($schedule->schoolPeriod->duration)
                        ->format('H:i:s'),
                ];
            }),
            'nextSession' => (bool) $this->nextSession ? [
                'batch_session_id' => $this->nextSession->id,
                'batch_schedule_id' => $this->nextSession->batchSchedule->id,
                'date' => $this->nextSession->date,
                'day_of_week' => $this->nextSession->batchSchedule->day_of_week,
                'period' => $this->nextSession->batchSchedule->schoolPeriod->name,
                'start_time' => $this->nextSession->batchSchedule->schoolPeriod->start_time,
                'duration' => $this->nextSession->batchSchedule->schoolPeriod->duration,
                'end_time' => Carbon::parse($this->nextSession->batchSchedule->schoolPeriod->start_time)
                    ->addMinutes($this->nextSession->batchSchedule->schoolPeriod->duration)
                    ->format('H:i:s'),
            ] : null,
            'lastSession' => (bool) $this->lastSession ? [
                'batch_session_id' => $this->lastSession->id,
                'batch_schedule_id' => $this->lastSession->batchSchedule->id,
                'date' => $this->lastSession->date,
                'day_of_week' => $this->lastSession->batchSchedule->day_of_week,
                'period' => $this->lastSession->batchSchedule->schoolPeriod->name,
                'start_time' => $this->lastSession->batchSchedule->schoolPeriod->start_time,
                'duration' => $this->lastSession->batchSchedule->schoolPeriod->duration,
                'end_time' => Carbon::parse($this->lastSession->batchSchedule->schoolPeriod->start_time)
                    ->addMinutes($this->lastSession->batchSchedule->schoolPeriod->duration)
                    ->format('H:i:s'),
            ] : null,
        ];
    }
}

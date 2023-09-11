<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchScheduleResource extends JsonResource
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
            'day' => $this->day_of_week,
            'school_period' => [
                'id' => $this->schoolPeriod->id,
                'name' => $this->schoolPeriod->name,
                'start_time' => $this->schoolPeriod->start_time,
                'duration' => $this->schoolPeriod->duration,
                'is_custom' => $this->schoolPeriod->is_custom,
            ],
            'batch_subject' => new BatchSubjectResource($this->batchSubject),
        ];
    }
}

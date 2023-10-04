<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolPeriodResource extends JsonResource
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
            'name' => $this->name,
            'start_time' => $this->start_time,
            'duration' => $this->duration,
            'is_custom' => $this->is_custom,
            'school_year' => $this->schoolYear->name,
            'order' => $this->order,
        ];
    }
}

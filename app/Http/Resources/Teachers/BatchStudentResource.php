<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $batchSubject = $this->batchSubject;

        return [
            'batch' => new BatchResource($batchSubject->batch),
            'student' => new StudentResource($this->student),
        ];
    }
}

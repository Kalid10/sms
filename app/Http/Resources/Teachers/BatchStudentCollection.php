<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BatchStudentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'batch' => $this->collection->first()->batch,
                'students' => StudentResource::collection($this->collection->transform(function ($batchStudent) {
                    return $batchStudent->student;
                })),
            ],
            'stats' => [
                'student_count' => $this->collection->count(),
            ],
        ];
    }
}

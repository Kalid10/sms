<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentAssessmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'assessment' => $this->collection->first()->assessment,
            'student_assessments' => StudentAssessmentGradeResource::collection($this->collection),
        ];
    }
}

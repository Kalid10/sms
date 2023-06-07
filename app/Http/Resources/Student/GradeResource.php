<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id' => $this->id,
            'student_name' => $this->user->name,
            'grade' => $this->studentGrades->map(function ($grade) {
                return [
                    'score' => $grade->score,
                    'gradable_id' => $grade->gradable->id,
                    'gradable_type' => $grade->gradable_type,
                    'gradable_name' => $grade->gradable->name,
                    'grade_scale_id' => $grade->gradeScale->id,
                    'grade_scale_state' => $grade->gradeScale->state,
                    'grade_scale_description' => $grade->gradeScale->description,
                ];
            }),

        ];
    }
}

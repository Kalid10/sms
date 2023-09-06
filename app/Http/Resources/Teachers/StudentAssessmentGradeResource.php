<?php

namespace App\Http\Resources\Teachers;

use Illuminate\Http\Request;

class StudentAssessmentGradeResource extends StudentAssessmentResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $assessment = $this->assessment;
        $student = $this->student;

        return [
            'student' => new StudentResource($student),
            'score' => [
                'max_point' => $assessment->maximum_point,
                'point' => $this->point,
                'status' => $this->status,
                'comment' => $this->comment,
                'grade' => $this->grade(
                    $this->point,
                    $assessment->maximum_point,
                    $assessment->school_year_id
                ),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources\Teachers;

use App\Models\GradeScale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $assessment = $this->assessment;

        return [
            'assessment' => new AssessmentResource($assessment),
            'student_assessment' => new StudentAssessmentGradeResource($this),
        ];
    }

    protected function grade($point, $maximum_point, $school_year_id): ?GradeScale
    {
        if ($point == null) {
            return null;
        }

        return GradeScale::get($point, $maximum_point, $school_year_id);
    }
}

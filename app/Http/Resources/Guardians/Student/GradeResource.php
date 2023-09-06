<?php

namespace App\Http\Resources\Guardians\Student;

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
            'student_profile_image' => $this->user->profile_image,
            'grades' => $this->grades->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'gradable' => [
                        ...$grade->gradable->toArray(),
                        'type' => $grade->gradable_type,
                        'school_year' => $this->schoolYearName($grade->gradable),
                    ],
                    'grade_scale' => $grade->gradeScale,
                    'score' => $grade->score,
                    'total_score' => $grade->total_score,
                    'rank' => $grade->rank,
                    'conduct' => $grade->conduct,
                    'attendance' => $grade->attendance,
                    'updated_at' => $grade->updated_at,
                ];
            }),

        ];
    }

    private function schoolYearName($gradable): string
    {
        return $gradable->schoolYear ? $gradable->load('schoolYear')->schoolYear->name : $gradable->name;
    }

    private function semesterName($gradable): ?string
    {
        return match (get_class($gradable)) {
            'App\Models\Quarter' => $gradable->load('semester')->semester->name,
            'App\Models\Semester' => $gradable->name,
            default => null,
        };
    }
}

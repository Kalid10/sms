<?php

namespace App\Http\Resources\Guardians\Student;

use App\Models\Assessment;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAssessmentsGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $assessment = $this->assessmentType;
        $gradeScale = $this->gradeScale;

        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'batch_subject_id' => $this->batch_subject_id,
            'score' => $this->score,
            'assessment_type' => [
                'id' => $assessment->id,
                'name' => $assessment->name,
                'percentage' => $assessment->percentage,
            ],
            'grade_scale' => [
                'id' => $gradeScale->id,
                'label' => $gradeScale->label,
                'state' => $gradeScale->state,
                'description' => $gradeScale->description,
            ],
            'count' => $this->count(),
        ];
    }

    private function getQuarterIds($gradableId, $gradableType): array
    {
        return match ($gradableType) {
            'App\Models\Quarter' => [$gradableId],
            'App\Models\Semester' => Semester::find($gradableId)->quarters->pluck('id')->toArray(),
            'App\Models\SchoolYear' => SchoolYear::find($gradableId)->quarters->pluck('id')->toArray(),
        };
    }

    private function count(): int
    {
        $studentId = $this->student_id;
        $assessmentTypeId = $this->assessment_type_id;
        $batchSubjectId = $this->batch_subject_id;
        $quarterIds = $this->getQuarterIds($this->gradable_id, $this->gradable_type);

        return StudentAssessment::with('assessment')
            ->where('student_id', $studentId)
            ->whereHas('assessment', function ($query) use ($assessmentTypeId, $batchSubjectId, $quarterIds) {
                $query
                    ->where('assessment_type_id', $assessmentTypeId)
                    ->where('batch_subject_id', $batchSubjectId)
                    ->whereIn('quarter_id', $quarterIds)
                    ->where('status', Assessment::STATUS_COMPLETED);
            })->count();
    }
}

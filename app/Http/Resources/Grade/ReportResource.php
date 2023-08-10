<?php

namespace App\Http\Resources\Grade;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $grade = $this['grade'];
        $subjects = $this['subjects'];

        return [
            'student_id' => $grade->student_id,
            'gradable_id' => $grade->gradable_id,
            'gradable_type' => $grade->gradable_type,
            'score' => $grade->score,
            'total_score' => $grade->total_score,
            'rank' => $grade->rank,
            'conduct' => $grade->conduct,
            'attendance' => $grade->attendance,
            'gradable' => [
                'id' => $grade->gradable->id,
                'name' => $grade->gradable->name,
                'semester_name' => $this->semesterName($grade->gradable),
                'school_year_name' => $this->schoolYearName($grade->gradable),
                'start_date' => $grade->gradable->start_date,
                'end_date' => $grade->gradable->end_date,
            ],
            'grade_scale' => [
                'school_year_id' => $grade->gradeScale->school_year_id,
                'label' => $grade->gradeScale->label,
                'state' => $grade->gradeScale->state,
                'description' => $grade->gradeScale->description,
            ],
            'subjects' => $subjects->map(function ($subject) {
                return [
                    'batch_subject_id' => $subject->batch_subject_id,
                    'subject' => [
                        'id' => $subject->batchSubject->subject->id,
                        'name' => $subject->batchSubject->subject->full_name,
                        'short_name' => $subject->batchSubject->subject->short_name,
                        'category' => $subject->batchSubject->subject->category,
                        'tags' => $subject->batchSubject->subject->tags,
                    ],
                    'teacher' => [
                        'id' => $subject->batchSubject->teacher->id,
                        'user_id' => $subject->batchSubject->teacher->user_id,
                        'name' => $subject->batchSubject->teacher->user->name,
                        'email' => $subject->batchSubject->teacher->user->email,
                        'username' => $subject->batchSubject->teacher->user->username,
                        'phone_number' => $subject->batchSubject->teacher->user->phone_number,
                        'profile_image' => $subject->batchSubject->teacher->user->profile_image,
                        'gender' => $subject->batchSubject->teacher->user->gender,
                    ],
                    'score' => $subject->score,
                    'total_score' => $subject->total_score,
                    'rank' => $subject->rank,
                    'conduct' => $subject->conduct,
                    'attendance' => $subject->attendance,
                    'grade_scale' => [
                        'label' => $subject->gradeScale->label,
                        'state' => $subject->gradeScale->state,
                        'description' => $subject->gradeScale->description,
                    ],
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

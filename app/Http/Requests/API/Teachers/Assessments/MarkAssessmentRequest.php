<?php

namespace App\Http\Requests\API\Teachers\Assessments;

use App\Models\Assessment;
use App\Models\Student;
use App\Models\StudentAssessment;
use Illuminate\Contracts\Validation\Rule;

class MarkAssessmentRequest extends AssessmentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $assessmentStatus = $this->route('assessment')->status;

        if (! in_array($assessmentStatus, [
            Assessment::STATUS_PUBLISHED,
            Assessment::STATUS_MARKING,
            Assessment::STATUS_COMPLETED,
        ])) {
            return false;
        }

        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'points' => 'required|array',
            'points.*.student_id' => 'required|exists:students,id',
            'points.*.point' => 'required|numeric|min:0',
            'points.*.comment' => 'nullable|string',
            'points.*.status' => 'nullable|string|in:'.implode(',', [
                StudentAssessment::STATUS_VALID_REASSESSMENT,
                StudentAssessment::STATUS_DISQUALIFIED,
                StudentAssessment::STATUS_MISCONDUCT,
            ]),
        ];
    }

    public function messages(): array
    {
        return [
            'points.*.student_id.required' => 'Student ID is required.',
            'points.*.student_id.exists' => 'Student ID does not exist.',
            'points.*.point.required' => 'Point is required.',
            'points.*.point.integer' => 'Point must be a number.',
            'points.*.point.min' => 'Point must be at least 0.',
            'points.*.comment.string' => 'Comment must be a string.',
            'points.*.status.string' => 'Status must be a string.',
            'points.*.status.in' => 'Status must be one of the following: '.implode(', ', [
                StudentAssessment::STATUS_VALID_REASSESSMENT,
                StudentAssessment::STATUS_DISQUALIFIED,
                StudentAssessment::STATUS_MISCONDUCT,
            ]),
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->ensureAssessmentStatusIsValid();
            $this->ensureAllStudentsCanTakeAssessments();
            $this->ensureStudentPointsDoesNotExceedMaximum();
        });
    }

    /**
     * @return void
     *
     * Invalidate if the assessment is not "published"
     */
    protected function ensureAssessmentStatusIsValid(): void
    {
        // TODO: Check the appropriate status (PUBLISHED, CLOSED, MARKING, etc...)
        if (! ($this->route('assessment')->status === Assessment::STATUS_MARKING || $this->route('assessment')->status === Assessment::STATUS_PUBLISHED)) {
            $this->validator->errors()->add('assessment', 'Assessment is not ready for marking');
        }
    }

    /**
     * @return void
     *
     * Invalidate if the student is not in the same batch as the assessment
     */
    protected function ensureAllStudentsCanTakeAssessments(): void
    {
        $studentIds = collect($this->input('points'))->pluck('student_id')->toArray();
        $students = Student::with('user', 'currentBatch')->whereIn('id', $studentIds)->get();

        $assessmentBatch = $this->route('assessment')->batchSubject->batch->id;

        foreach ($students as $student) {
            if ($student->currentBatch[0]->id !== $assessmentBatch) {
                $this->validator->errors()->add('points.*.student_id', 'Student '.$student->user->name.' cannot take this assessment.');
            }
        }
    }

    /**
     * @return void
     *
     * Invalidate if the student's points exceed the maximum points
     */
    protected function ensureStudentPointsDoesNotExceedMaximum(): void
    {
        $maxPoints = $this->route('assessment')->maximum_point;

        foreach ($this->input('points') as $key => $point) {
            if ($point['point'] > $maxPoints) {
                $this->validator->errors()
                    ->add(
                        'points.'.$key.'.point',
                        Student::find($point['student_id'])->user->name.
                        '\'s  points cannot exceed '.
                        $maxPoints
                    );
            }
        }
    }
}

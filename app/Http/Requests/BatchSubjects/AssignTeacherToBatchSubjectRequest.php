<?php

namespace App\Http\Requests\BatchSubjects;

use App\Models\Teacher;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignTeacherToBatchSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => 'exists:teachers,id|required',
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->ensureBatchSubjectWeeklyFrequencyIsSet();
            $this->ensureTeacherWeeklySessionsDoesNotExceedMax();
        });
    }

    private function ensureBatchSubjectWeeklyFrequencyIsSet(): void
    {
        $frequency = $this->route('batchSubject')->weekly_frequency;
        if (is_null($frequency)) {
            $this->validator->errors()->add('batch_subject', 'You need to set the weekly frequency for this class before you assign it a teacher.');
        }
    }

    private function ensureTeacherWeeklySessionsDoesNotExceedMax(): void
    {
        $teacher = Teacher::find($this->input('teacher_id'));
        $batchSubject = $this->route('batchSubject');

        $currentTeacherSessions = $teacher->load('activeBatchSubjects')->activeBatchSubjects->pluck('weekly_frequency')->sum();

        if (($currentTeacherSessions + $batchSubject->weekly_frequency) > env('TEACHER_WEEKLY_MAX_SESSIONS')) {
            $this->validator->errors()->add('teacher_id', 'This teacher cannot take this class because it will exceed their weekly session limit');
        }
    }
}

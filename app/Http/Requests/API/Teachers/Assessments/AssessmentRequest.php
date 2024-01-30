<?php

namespace App\Http\Requests\API\Teachers\Assessments;

use App\Http\Requests\API\Teachers\Request;
use Illuminate\Contracts\Validation\ValidationRule;

class AssessmentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('assessment')) {

            $batchSubjectTeacher = $this->route('assessment')
                ->load('batchSubject.teacher')
                ->batchSubject
                ->teacher
                ->id;

            if ($this->teacher()->id !== $batchSubjectTeacher) {
                return false;
            }
        }

        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'active' => 'nullable|boolean',
            'status' => 'nullable|array',
            'status.*' => 'in:completed,marking,published,scheduled,draft',
            'batch_session_id' => 'nullable|integer|exists:batch_sessions,id',
            'query' => 'nullable|string',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'assessment_type_ids' => 'nullable|array',
            'assessment_type_ids.*' => 'nullable|integer|exists:assessment_types,id',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'nullable|integer|exists:subjects,id',
        ];
    }
}

<?php

namespace App\Http\Requests\API\Teachers\Assessments;

use App\Models\BatchSubject;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only teachers can create assessments
        return auth()->user()->isTeacher();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'batch_subject_id' => 'required|integer|exists:batch_subjects,id',
            'title' => 'required|string',
            'description' => 'string',
            'maximum_point' => 'required|integer|min:1|max:100',
            'type' => 'string',
        ];

        $rules['due_date'] = $this->input('type') === 'Classwork' ? 'nullable|date' : 'required|date';

        return $rules;
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // Get the single batch subject ID
            $batchSubjectId = $this->batch_subject_id;
            $batchSubject = BatchSubject::find($batchSubjectId);

            // Check if batch subject is active
            if (! $batchSubject->isActive()) {
                return $validator->errors()->add('batch_subject_id', 'Batch subject is not active.');
            }

            // Check if teacher teaches the subject
            if (! auth()->user()->teacher->batchSubjects->contains($batchSubject)) {
                return $validator->errors()->add('batch_subject_id', 'You do not teach this subject.');
            }
        });
    }
}

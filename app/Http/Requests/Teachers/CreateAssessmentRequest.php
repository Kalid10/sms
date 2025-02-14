<?php

namespace App\Http\Requests\Teachers;

use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
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
        return [
            'batch_subject_ids' => 'required|array',
            'batch_subject_ids.*' => 'required|integer|exists:batch_subjects,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'due_date' => 'required|date|after_or_equal:today',
            'title' => 'required|string',
            'description' => 'string',
            'maximum_point' => 'required|integer|min:1|max:100',
            'lesson_plan_id' => 'nullable|exists:lesson_plans,id',
            'status' => 'nullable|string|in:draft,published,scheduled',
        ];
    }

    public function messages(): array
    {
        return [
            'batch_subject_ids.required' => 'Batch subject is required.',
            'batch_subject_ids.exists' => 'Batch subject does not exist.',
            'assessment_type_id.required' => 'Assessment type is required.',
            'assessment_type_id.exists' => 'Assessment type does not exist.',
            'due_date.required' => 'Due date is required.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // Check if batch subject is active and if teacher teaches the subject and if assessment type is active for multiple batch subject ids
            foreach ($this->batch_subject_ids as $batchSubjectId) {
                $batchSubject = BatchSubject::find($batchSubjectId);
                if (! $batchSubject->isActive()) {
                    return $validator->errors()->add('batch_subject_id', 'Batch subject is not active.');
                }

                // Check if teacher teaches the subject
                if (! auth()->user()->teacher->batchSubjects->contains($batchSubject)) {
                    return $validator->errors()->add('batch_subject_id', 'You do not teach this subject.');
                }

                // Check if assessment type is active
                $assessmentType = AssessmentType::find($this->assessment_type_id);
                if ($assessmentType->school_year_id !== SchoolYear::getActiveSchoolYear()->id) {
                    return $validator->errors()->add('assessment_type_id', 'Invalid assessment type.');
                }
            }
            // If assessment type is not customizable, check if it has reached its maximum count for this quarter
            $assessmentType = AssessmentType::find($this->assessment_type_id);
            if (! $assessmentType->customizable) {
                foreach ($this->batch_subject_ids as $batchSubjectId) {
                    $assessments = Assessment::where([
                        ['assessment_type_id', $this->assessment_type_id],
                        ['batch_subject_id', $batchSubjectId],
                        ['quarter_id', Quarter::getActiveQuarter()->id],
                    ]);

                    if ($assessments->count() >= $assessmentType->max_assessments) {
                        return $validator->errors()->add('assessment_type_id', $assessmentType->name.' has reached its maximum count for this quarter.');
                    }
                }
            }

        });
    }
}

<?php

namespace App\Http\Requests\Batches;

use App\Models\BatchSubject;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignSubjectTeacherRequest extends FormRequest
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
            'batch_subjects_teachers' => 'required|array',
            'batch_subjects_teachers.*.teacher_id' => 'required|integer|exists:teachers,id',
            'batch_subjects_teachers.*.batch_subject_id' => 'required|integer|exists:batch_subjects,id',

        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if batch subject is active
            foreach ($this->batch_subjects_teachers as $batchSubjectTeacher) {
                $batchSubject = BatchSubject::find($batchSubjectTeacher['batch_subject_id']);

                if (isset($batchSubject->batch->schoolYear->end_date)) {
                    $validator->errors()->add('batch_subjects_teachers', 'The batch subject with ID '.$batchSubjectTeacher['batch_subject_id'].' is not active.');
                }

                // Check if the batch subject already has a teacher
                if (isset($batchSubject->teacher_id)) {
                    $validator->errors()->add('batch_subjects_teachers', 'The batch subject with ID '.$batchSubjectTeacher['batch_subject_id'].' already has a teacher.');
                }
            }
        });
    }
}

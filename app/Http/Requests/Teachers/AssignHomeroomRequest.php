<?php

namespace App\Http\Requests\Teachers;

use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignHomeroomRequest extends FormRequest
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
            'teacher_id' => 'required|integer|exists:teachers,id',
            'batch_id' => 'required|integer|exists:batches,id',
            'replace' => 'required|boolean',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if batch exists
            $batch = Batch::find($this->batch_id);
            if (! $batch) {
                return $validator->errors()->add('batch_id', 'Batch does not exist.');
            }

            // Check if teacher exists
            $teacher = Teacher::find($this->teacher_id);
            if (! $teacher) {
                return $validator->errors()->add('teacher_id', 'Teacher does not exist.');
            }

            // Check if batch is active
            if (isset($batch->schoolYear->end_date)) {
                return $validator->errors()->add('batch_id', 'Batch school year is not active.');
            }

            // If replace is false, check if teacher is already assigned to the same batch
            if (! $this->replace) {
                if (HomeroomTeacher::where('teacher_id', $this->teacher_id)->where('batch_id', $this->batch_id)->exists()) {
                    return $validator->errors()->add('teacher_id', $teacher->user->name.' is already assigned to '.$batch->level->name.' '.$batch->section);
                }
            }
        });
    }
}

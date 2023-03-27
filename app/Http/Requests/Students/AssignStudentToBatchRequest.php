<?php

namespace App\Http\Requests\Students;

use App\Models\Batch;
use App\Models\BatchStudent;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignStudentToBatchRequest extends FormRequest
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
            'batch_id' => 'required|exists:batches,id',
            'student_id' => 'required|exists:students,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $batch = Batch::find($this->batch_id);

            // Check if batch is active
            if (isset($batch->schoolYear->end_date)) {
                return $validator->errors()->add('batch_id', 'Batch school year is not active.');
            }

            $activeSchoolYear = $batch->schoolYear;

            // Check if student is registered in the same school year
            $studentAssigned = BatchStudent::where('student_id', $this->student_id)
                ->whereHas('batch', function ($query) use ($activeSchoolYear) {
                    $query->where('school_year_id', $activeSchoolYear->id);
                })
                ->exists();

            if ($studentAssigned) {
                $validator->errors()->add('student_id', "The student is already assigned to {$batch->level->name} {$batch->section}  on  {$activeSchoolYear->name}");
            }
        });
    }
}

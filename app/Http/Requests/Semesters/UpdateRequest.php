<?php

namespace App\Http\Requests\Semesters;

use App\Models\Semester;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:semesters,id',
            'name' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'date|nullable|after:start_date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the semester status is not completed
            $semester = Semester::find($this->get('id'));
            if ($semester->status === Semester::STATUS_COMPLETED) {
                return $validator->errors()->add('semesters', 'The semester status is already completed, you cannot update it anymore.');
            }

            // Check if there is a semester with the same name on the same school year but not the same semester being updated
            if (Semester::where('name', $this->get('name'))->where('school_year_id', $semester->school_year_id)->where('id', '!=', $semester->id)->exists()) {
                Log::error('Semester with the same name on the same school year already exists');
                $validator->errors()->add('semesters', $this->get('name').' is already a semester registered on the same school year.');
            }
        });
    }
}

<?php

namespace App\Http\Requests\Semesters;

use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CreateRequest extends FormRequest
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
            'semesters' => 'required|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if there is an ongoing academic year
            if (! SchoolYear::where('end_date', null)->exists()) {
                return $validator->errors()->add('semesters', 'There is no ongoing academic year.');
            }

            // Loop through the semesters and validate each semester
            foreach ($this->get('semesters') as $semester) {
                $semesterValidator = Validator::make($semester, [
                    'name' => 'required',
                    'start_date' => 'required|date|after_or_equal:now',
                    'end_date' => 'date|after:start_date',
                ]);

                // Check if there is already a semester with the same name on the same school year
                if (Semester::where('name', $semester['name'])->where('school_year_id', SchoolYear::where('end_date', null)->first()->id)->exists()) {
                    $validator->errors()->add('semesters', $semester['name'].' is already a semester registered on the same school year.');
                }
            }

            // Check if there is error on the semester validator
            if ($semesterValidator->fails()) {
                $validator->errors()->add('semesters', $semesterValidator->errors());
            }
        });
    }
}

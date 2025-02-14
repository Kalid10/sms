<?php

namespace App\Http\Requests\Batches;

use App\Models\Batch;
use App\Models\SchoolYear;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'level_id' => 'required|exists:levels,id',
            'section' => 'required|string|max:2',
            'min_students' => 'nullable|integer|min:5|lt:max_students',
            'max_students' => 'nullable|integer|min:5|gt:min_students',
        ];
    }

    // Check if section is unique for level and school year
    public function withValidator($validator)
    {
        // Get active school year
        $schoolYear = SchoolYear::whereNull('end_date')->first();

        // Return error if there is no active school year
        if (! $schoolYear) {
            return $validator->errors()->add('school_year', 'School year is not active');
        }

        $validator->after(function ($validator) use ($schoolYear) {
            $batch = Batch::where('level_id', $this->level_id)
                ->where('school_year_id', $schoolYear->id)
                ->where('section', $this->section)
                ->first();

            if ($batch) {
                $validator->errors()->add('section', 'Section already exists for level and school year.');
            }
        });

        // Convert section to uppercase
        $this->section = strtoupper($this->section);
    }
}

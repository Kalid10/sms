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
        ];
    }

    // Check if section is unique for level and school year
    public function withValidator($validator)
    {
        // Get active school year
        $schoolYear = SchoolYear::whereNull('end_date')->first();
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

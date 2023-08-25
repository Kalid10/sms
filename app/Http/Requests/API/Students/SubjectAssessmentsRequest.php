<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\ValidationRule;

class SubjectAssessmentsRequest extends BatchSubjectRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'gradable_type' => 'required|in:App\Models\Quarter,App\Models\Semester,App\Models\SchoolYear',
            'gradable_id' => 'required',
        ];
    }
}

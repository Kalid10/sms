<?php

namespace App\Http\Requests\API\Teachers\Assessments;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateAssessmentRequest extends AssessmentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'maximum_point' => 'required|integer',
            'status' => 'required|string|in:draft,published,closed,marking,completed,scheduled',
            'due_date' => 'required|date',
        ];
    }
}

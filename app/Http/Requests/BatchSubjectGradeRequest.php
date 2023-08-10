<?php

namespace App\Http\Requests;

use App\Http\Requests\API\Students\BatchSubjectRequest;

class BatchSubjectGradeRequest extends BatchSubjectRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|string>
     */
    public function rules(): array
    {
        return [
            'gradable_type' => 'nullable|in:App\Models\Quarter,App\Models\Semester,App\Models\SchoolYear',
        ];
    }
}

<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\Rule;

class GradeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'gradable_type' => 'nullable|in:App\Models\Quarter,App\Models\Semester,App\Models\SchoolYear',
            'active' => 'nullable|boolean',
        ];
    }
}

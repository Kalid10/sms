<?php

namespace App\Http\Requests\API\Teachers\BatchSubjects;

use App\Http\Requests\API\Teachers\Request;
use Illuminate\Contracts\Validation\ValidationRule;

class BatchSubjectRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'active' => 'nullable|boolean',
        ];
    }
}

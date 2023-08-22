<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\ValidationRule;

class TermRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:quarter,semester,school_year',
        ];
    }
}

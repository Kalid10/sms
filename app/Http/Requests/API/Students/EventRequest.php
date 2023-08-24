<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\ValidationRule;

class EventRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'range' => 'nullable|in:month,day',
            'date' => 'nullable|required_with:range|date',
        ];
    }
}

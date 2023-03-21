<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'email' => 'required_without_all:phone_number,username|required_if:type,admin|email|unique:users',
            'phone_number' => 'required_without_all:email,username|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users',
            'username' => 'required_without_all:email,phone_number|exclude_unless:type,student|string|min:6|unique:users',
            'level_id' => 'required_if:type,student|exists:App\Models\Level,id',
            'guardian_id' => 'required_if:type,student|exists:App\Models\Guardian,id',
            'position' => 'required_if:type,admin',
        ];
    }
}

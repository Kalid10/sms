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
            'type' => 'required|string|in:admin,student,teacher',
            'email' => 'required_without_all:phone_number,username|required_if:type,admin|email|unique:users',
            'phone_number' => 'required_without_all:email,username|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users',
            'gender' => 'required|string|max:255',
            'date_of_birth' => 'required_if:type,student|date',
            'username' => 'required_without_all:email,phone_number|exclude_unless:type,student|string|min:6|unique:users',
            'position' => 'required_if:type,admin',
            'guardian_name' => 'required_if:type,student',
            'guardian_email' => 'required_if:type,student|email|unique:users,email',
            'guardian_phone_number' => 'required_if:type,student|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users,phone_number',
            'level_id' => 'required_if:type,student|exists:levels,id',
            'guardian_gender' => 'required_if:type,student|string|max:255',
        ];
    }
}

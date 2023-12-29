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
        // Common rules for both new and existing parents
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:admin,student,teacher',
            'email' => 'required_if:type,admin|nullable|email|unique:users',
            'phone_number' => 'required_if:type,guardian,admin|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users',
            'gender' => 'required|string|max:255',
            'date_of_birth' => 'required_if:type,student|date',
            'position' => 'required_if:type,admin',
            'existing_guardian_id' => 'nullable|exists:guardians,id',
            'batch_id' => 'required_if:type,student|exists:batches,id',
        ];

        if ($this->input('existing_guardian_id')) {
            // Optional fields for existing parents
            $rules += [
                'guardian_relation' => 'nullable|string|max:255',
                'guardian_name' => 'nullable',
                'guardian_email' => 'nullable|email|unique:users,email',
                'guardian_phone_number' => 'nullable|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users,phone_number',
                'guardian_gender' => 'nullable|string|max:255',
            ];
        } else {
            // Required fields for new parents
            $rules += [
                'guardian_relation' => 'string|max:255',
                'guardian_name' => 'required_if:type,student',
                'guardian_email' => 'email|unique:users,email',
                'guardian_phone_number' => 'required_if:type,student|regex:/(09)[0-9]{8}/|max:10|min:10|unique:users,phone_number',
                'guardian_gender' => 'required_if:type,student|string|max:255',
            ];
        }

        return $rules;
    }
}

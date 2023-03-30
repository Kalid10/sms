<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|email',
            'username' => 'required|string|max:255|unique:users|username',
            'phone_number' => 'required|string|max:255|unique:users,phone_number',
        ];
    }

    // Check if the logged in user is the same as the user being updated
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->id != auth()->user()->id) {
                $validator->errors()->add('id', 'You are not authorized to update this user.');
            }
        });
    }
}

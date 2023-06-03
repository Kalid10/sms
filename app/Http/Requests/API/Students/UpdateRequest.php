<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->guardian->children->contains($this->route('student'));
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
            'email' => 'required|string|email|max:255|unique:users|email',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:255|unique:users,phone_number',
            'gender' => 'required|string|max:255',
            'sub_city' => 'string|max:255',
            'woreda' => 'string|max:255',
            'house_number' => 'string|max:255',
        ];
    }
}

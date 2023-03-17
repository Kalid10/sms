<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'full_name' => 'required|unique:subjects,full_name',
            'short_name' => 'required|unique:subjects,short_name',
        ];
    }

    // override the messages function to return custom messages
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required',
            'full_name.unique' => $this->full_name.' has already been added!',
            'short_name.required' => 'Short name is required',
            'short_name.unique' => $this->short_name.' has already been added!',
        ];
    }
}

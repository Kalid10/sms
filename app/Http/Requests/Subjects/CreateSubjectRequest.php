<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubjectRequest extends FormRequest
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
            'name' => 'required|unique:subjects,name',
        ];
    }

    // override the messages function to return custom messages
    public function messages(): array
    {
        return [
            'name.required' => 'Subject name is required',
            'name.unique' => $this->name.' has already been added!',
        ];
    }
}

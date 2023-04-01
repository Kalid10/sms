<?php

namespace App\Http\Requests\Subjects;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBulkRequest extends FormRequest
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
            'subjects' => 'required|array',
            'subjects.*.full_name' => 'required|string|unique:subjects,full_name',
            'subjects.*.short_name' => 'required|string|unique:subjects,short_name',
            'subjects.*.category' => 'required|string',
            'subjects.*.tags' => 'nullable|array',

        ];
    }

    public function messages()
    {
        return [
            'subjects.*.full_name.required' => 'Full name is required',
            'subjects.*.full_name.unique' => 'Name has already been added, please use a different name!',
            'subjects.*.short_name.required' => 'Short name is required',
            'subjects.*.short_name.unique' => 'Short name has already been added, please use a different name!',
            'subjects.*.category.required' => 'Category is required',
        ];
    }
}

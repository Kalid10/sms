<?php

namespace App\Http\Requests\Subjects;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
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
            'id' => 'required|exists:subjects,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if subject full name and short name exists other than the subject being updated
            if (Subject::where('full_name', $this->get('full_name'))->where('id', '!=', $this->get('id'))->exists()) {
                $validator->errors()->add('full_name', $this->full_name.' has already been added!');
            }

            if (Subject::where('short_name', $this->get('short_name'))->where('id', '!=', $this->get('id'))->exists()) {
                $validator->errors()->add('short_name', $this->short_name.' has already been added!');
            }
        });
    }

    // override the messages function to return custom messages
    public function messages(): array
    {
        return [
            'id.required' => 'Subject id is required',
            'id.exists' => 'Subject does not exist',
            'full_name.required' => 'Full name is required',
            'short_name.required' => 'Short name is required',
        ];
    }
}

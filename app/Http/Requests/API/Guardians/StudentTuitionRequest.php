<?php

namespace App\Http\Requests\API\Guardians;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentTuitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('studentTuition')) {
            return auth()->user()->guardian->id ===
                $this->route('studentTuition')
                    ->load('student.guardian')
                    ->student
                    ->guardian
                    ->id;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'nullable|exists:students,id',
            'status' => 'nullable|in:paid,unpaid',
        ];
    }
}

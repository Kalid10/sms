<?php

namespace App\Http\Requests\API;

use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StudentNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // authorizing the request if the logged-in user is a teacher
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user->type == User::TYPE_TEACHER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->checkIfStudentIsEnrolledInTeacherBatch($this->route('student'))) {
                $validator->errors()->add('student', 'Unauthorized action - student is not enrolled in your batch');
            }
        });
    }

    private function checkIfStudentIsEnrolledInTeacherBatch(Student $student): bool
    {
        $user = Auth::user();

        if ($user->type != User::TYPE_TEACHER) {
            return false;
        }

        $teacherBatches = $user->teacher->batchSubjects()->get()->filter(function ($batchSubject) {
            return $batchSubject->with('active');
        })->pluck('batch_id');

        if (! $teacherBatches->contains($student->activeBatch()->id)) {
            return false;
        }

        return true;
    }
}

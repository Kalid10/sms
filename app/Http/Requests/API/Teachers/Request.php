<?php

namespace App\Http\Requests\API\Teachers;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $teacher = $this->teacher();

        if ($this->route('batchSubject')) {
            return $teacher->id === $this->route('batchSubject')->teacher->id;
        }

        return true;
    }

    protected function teacher()
    {
        return auth()->user()->load('teacher')->teacher;
    }
}

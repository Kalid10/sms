<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('student')) {
            return $this->user()->guardian->children->contains($this->route('student'));
        }

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
            //
        ];
    }

    /**
     * Handle a failed authorization attempt.
     *
     *
     * @throws AuthorizationException
     */
    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('This action is unauthorized.');
    }
}

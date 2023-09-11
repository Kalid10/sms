<?php

namespace App\Http\Requests\API\Teachers\Assessments;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateAssessmentStatusRequest extends AssessmentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        switch ($this->input('new_status')) {

            case 'marking':
                if ($this->route('assessment')->status !== 'published') {
                    return false;
                }
                break;

            case 'completed':

                if ($this->route('assessment')->status !== 'marking') {
                    return false;
                }
                break;

            case 'published':

                if ($this->route('assessment')->status !== 'completed') {
                    return false;
                }
                break;

            case 'scheduled':

                if ($this->route('assessment')->status !== 'draft') {
                    return false;
                }
                break;

            case 'draft':

                if ($this->route('assessment')->status !== 'scheduled') {
                    return false;
                }
                break;

            default:
                return false;

        }

        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'new_status' => 'required|string|in:completed,marking,published,scheduled,draft',
        ];
    }
}

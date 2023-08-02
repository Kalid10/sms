<?php

namespace App\Http\Requests\API\Students;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BatchSubjectRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('student')) {
            $studentsBatch = collect([$this->route('student')->load('currentBatch')->currentBatch->first()->id]);
        } else {
            $studentsBatch = Auth::user()->guardian
                ->load('children')->children
                ->load('currentBatch')->pluck('currentBatch')
                ->flatten()->pluck('id');
        }

        $subjectBatch = $this->route('batchSubject')->batch_id;

        return parent::authorize() && $studentsBatch->contains($subjectBatch);
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
}

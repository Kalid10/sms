<?php

namespace App\Http\Requests\LessonPlans;

use App\Models\BatchSession;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateOrCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Checking if the teacher is owner of the batch subject
        $loggedInTeacherSubjectIds = Auth::user()->teacher->batchSubjects->pluck('id');

        // Check if the batch session id is owned by the logged in teacher
        $batchSessionId = $this->input('batch_session_id');
        $batchSessionSubjectId = BatchSession::find($batchSessionId)->load('batchSubject')->batchSubject->id;
        if (! $loggedInTeacherSubjectIds->contains($batchSessionSubjectId)) {
            Log::info('batch subject id '.$batchSessionSubjectId);

            return false;
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
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'batch_session_id' => 'required|integer|exists:batch_sessions,id',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the batch session status is scheduled unless throw validation error
            $batchSessionId = $this->input('batch_session_id');
            $batchSession = BatchSession::find($batchSessionId);
            if ($batchSession->status !== BatchSession::STATUS_SCHEDULED) {
                $validator->errors()->add('batch_session_id', 'You can not update a lesson plan for a session from the past, please check if there i a session in the past.');
            }
        });
    }
}

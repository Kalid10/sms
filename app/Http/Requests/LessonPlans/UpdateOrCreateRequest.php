<?php

namespace App\Http\Requests\LessonPlans;

use App\Models\BatchSession;
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

        // Check if all the batch session ids are owned by the logged in teacher\
        $batchSessionIds = $this->input('batch_session_ids');
        foreach ($batchSessionIds as $batchSessionId) {
            $batchSessionSubjectId = BatchSession::find($batchSessionId)->load('batchSubject')->batchSubject->id;
            if (! $loggedInTeacherSubjectIds->contains($batchSessionSubjectId)) {
                Log::info('batch subject id '.$batchSessionSubjectId);

                return false;
            }
        }

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
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'batch_session_ids' => 'required|array|min:1',
            'batch_session_ids.*' => 'required|integer|exists:batch_sessions,id',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if all batch sessions status is scheduled unless throw validation error
            $batchSessionIds = $this->input('batch_session_ids');
            foreach ($batchSessionIds as $batchSessionId) {
                $batchSession = BatchSession::find($batchSessionId);
                if ($batchSession->status !== BatchSession::STATUS_SCHEDULED) {
                    $validator->errors()->add('batch_session_ids', 'You can not update a lesson plan for a session from the past, please check if there i a session in the past.');
                }
            }
        });
    }
}

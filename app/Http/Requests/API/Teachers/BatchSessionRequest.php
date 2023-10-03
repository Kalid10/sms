<?php

namespace App\Http\Requests\API\Teachers;

use Illuminate\Support\Facades\Log;

class BatchSessionRequest extends Request
{
    public function authorize(): bool
    {
        Log::info('authorizing BatchSessionController@index');

        if ($this->route('batchSession')) {
            $batchSubjectTeacher = $this->route('batchSession')
                ->load('batchSchedule.batchSubject')
                ->batchSchedule->batchSubject->teacher_id;

            $batchSessionTeacher = $this->route('batchSession')->teacher_id;

            // Check if the teacher is the teacher of the batch subject or the teacher of the batch session
            return in_array(parent::teacher()->id, [$batchSubjectTeacher, $batchSessionTeacher]);
        }

        return parent::authorize();
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|in:in_progress',
            'force' => 'nullable|boolean',
        ];
    }
}

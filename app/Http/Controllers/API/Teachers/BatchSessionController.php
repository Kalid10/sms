<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\Request;
use App\Http\Resources\Teachers\BatchSessionCollection;
use App\Http\Resources\Teachers\BatchSessionResource;
use App\Models\Batch;
use App\Models\BatchSession;

class BatchSessionController extends Controller
{
    /**
     * @return BatchSessionCollection|BatchSessionResource
     *
     * Get all batch sessions of a teacher
     */
    public function index(Request $request, ?BatchSession $batchSession): BatchSessionCollection|BatchSessionResource
    {
        if ($batchSession->exists) {
            return new BatchSessionResource($batchSession);
        }

        $teacher = auth()->user()->load('teacher')->teacher;

        $batchSessions = $teacher->load([
            'batchSessions.batchSchedule.schoolPeriod',
            'batchSessions.batchSchedule.batchSubject.batch.level',
            'batchSessions.batchSchedule.batchSubject.batch.schoolYear',
            'batchSessions.batchSchedule.batchSubject.subject',
        ])->batchSessions->sortBy('date');

        return new BatchSessionCollection($batchSessions);
    }
}

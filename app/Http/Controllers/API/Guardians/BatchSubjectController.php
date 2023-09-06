<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Requests\API\Students\BatchSubjectRequest;
use App\Http\Resources\Guardians\Student\BatchSubjectResource;
use App\Models\BatchSubject;

class BatchSubjectController extends Controller
{
    public function index(BatchSubjectRequest $request, BatchSubject $batchSubject): BatchSubjectResource
    {
        $batchSubject = $batchSubject->load([
            'batch.level',
            'subject',
            'nextSession.batchSchedule.schoolPeriod',
            'lastSession.batchSchedule.schoolPeriod',
            'schedule.schoolPeriod',
            'teacher.user',
        ]);

        return new BatchSubjectResource($batchSubject);
    }
}

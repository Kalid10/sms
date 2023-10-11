<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\BatchSessionRequest;
use App\Http\Resources\Teachers\BatchSessionCollection;
use App\Http\Resources\Teachers\BatchSessionResource;
use App\Http\Resources\Teachers\EmptyResource;
use App\Models\BatchSession;
use Illuminate\Support\Facades\Log;

class BatchSessionController extends Controller
{
    /**
     * @return BatchSessionCollection|BatchSessionResource|EmptyResource
     *
     * Get all batch sessions of a teacher
     */
    public function index(BatchSessionRequest $request, ?BatchSession $batchSession): BatchSessionCollection|BatchSessionResource|EmptyResource
    {
        Log::info('inside BatchSessionController@index');
        Log::info('teachers active Batches '.json_encode(parent::teacher()->activeBatches->pluck('id')));

        parent::teacher()->activeBatches()->each(function ($batch) use ($request) {
            $batch->getSessions($request->input('force', false));
        });

        if ($batchSession->exists) {
            return new BatchSessionResource($batchSession->load([
                'batchSchedule.schoolPeriod',
                'batchSchedule.batchSubject.batch.level',
                'batchSchedule.batchSubject.batch.schoolYear',
                'batchSchedule.batchSubject.subject',
            ]));
        }

        if ($request->has('status') && $request->input('status') === 'in_progress') {
            if (parent::teacher()->inProgressBatchSession) {
                return new BatchSessionResource(parent::teacher()->inProgressBatchSession->load([
                    'batchSchedule.schoolPeriod',
                    'batchSchedule.batchSubject.batch.level',
                    'batchSchedule.batchSubject.batch.schoolYear',
                    'batchSchedule.batchSubject.subject',
                ]));
            }

            return new EmptyResource([]);
        }

        if ($request->has('batch_subject_id')) {

            $batchSessions = BatchSession::where('batch_subject_id', $request->input('batch_subject_id'))
                ->when($request->has('status'), function ($query) {
                    return $query->where('status', $this->input('status'));
                })
                ->get()
                ->sortBy('date');

            return new BatchSessionCollection($batchSessions);
        }

        $batchSessions = parent::teacher()->load([
            'batchSessions.batchSchedule.schoolPeriod',
            'batchSessions.batchSchedule.batchSubject.batch.level',
            'batchSessions.batchSchedule.batchSubject.batch.schoolYear',
            'batchSessions.batchSchedule.batchSubject.subject',
        ])->batchSessions->sortBy('date');

        return new BatchSessionCollection($batchSessions);
    }
}

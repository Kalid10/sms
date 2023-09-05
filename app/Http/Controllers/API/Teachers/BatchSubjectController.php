<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\BatchSubjects\BatchSubjectRequest;
use App\Http\Requests\API\Teachers\Request;
use App\Http\Resources\Teachers\BatchStudentCollection;
use App\Http\Resources\Teachers\BatchSubjectCollection;
use App\Http\Resources\Teachers\BatchSubjectResource;
use App\Models\Batch;
use App\Models\BatchSubject;

class BatchSubjectController extends BatchController
{
    public function index(BatchSubjectRequest $request, ?BatchSubject $batchSubject): BatchSubjectCollection|BatchSubjectResource
    {
        if ($batchSubject->exists) {

            return new BatchSubjectResource($batchSubject->load([
                'batch.schoolYear',
                'batch.level.levelCategory',
                'subject',
            ]));

        }

        $teacher = auth()->user()->load('teacher')->teacher;

        $batchSubjects = $teacher
            ->load(
                'batchSubjects.batch.schoolYear',
                'batchSubjects.batch.level.levelCategory',
                'batchSubjects.subject'
            )
            ->batchSubjects
            ->when($request->has('active'), function ($query) use ($request) {

                if ($request->input('active')) {
                    return $this->filterActiveBatchSubjects($query);
                }

                return $query;
            }, function ($query) {
                return $this->filterActiveBatchSubjects($query);
            });

        return new BatchSubjectCollection($batchSubjects);
    }

    public function batchSubjectStudents(Request $request, BatchSubject $batchSubject): BatchStudentCollection
    {
        return parent::students($request, $batchSubject->batch_id);
    }

    private function filterActiveBatchSubjects($query)
    {
        return $query->whereIn('batch_id', Batch::active()->pluck('id'));
    }
}

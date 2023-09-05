<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\BatchRequest;
use App\Http\Resources\Teachers\BatchStudentCollection;
use App\Models\Batch;
use App\Models\BatchStudent;

class BatchController extends Controller
{
    /**
     * @return BatchStudentCollection
     *
     * Return the students of the given batch subject.
     */
    public function students(BatchRequest $request, Batch $batch): BatchStudentCollection
    {
        $batchStudents = BatchStudent::with([
            'batch.level.levelCategory',
            'student.user',
        ])->where([
            'batch_id' => $batch->id,
        ])->get();

        return new BatchStudentCollection($batchStudents);
    }
}

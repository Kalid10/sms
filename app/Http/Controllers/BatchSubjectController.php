<?php

namespace App\Http\Controllers;

use App\Http\Requests\Batches\AssignSubjectsRequest;
use App\Models\BatchSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BatchSubjectController extends Controller
{
    public function assign(AssignSubjectsRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        // Loop through the batchesSubjects
        foreach ($request->batches_subjects as $batchData) {
            $batchId = $batchData['batch_id'];

            // Loop through the subject_ids in each batch
            foreach ($batchData['subject_ids'] as $subjectId) {
                // Create a new batch subject
                BatchSubject::create([
                    'batch_id' => $batchId,
                    'subject_id' => $subjectId,
                ]);
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Batch subject added successfully.');
    }
}

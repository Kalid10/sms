<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Students\AssignStudentToBatchRequest;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BatchStudentController extends Controller
{
    public function add(AssignStudentToBatchRequest $request): RedirectResponse
    {
        $batchStudent = BatchStudent::create($request->validated());

        $studentName = $batchStudent->student->user->name;
        $batch = $batchStudent->batch;
        $batchName = $batch->level->name.$batch->section;

        return redirect()->back()->with('success', "{$studentName} has been assigned to {$batchName} successfully.");
    }

    public function getBatchStudents(Request $request): Response
    {
        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
        ]);

        return Inertia::render('Welcome', [
            'batchStudents' => BatchStudent::where('batch_id', $request->input('batch_id'))->with('student.user')->get(),
        ]);
    }

    public function transfer(Request $request): RedirectResponse
    {
        $request->validate([
            'destination_batch_id' => 'required|integer|exists:batches,id',
            'student_id' => 'required|integer|exists:students,id',
        ]);

        // Check if the batch is an active batch
        $destinationBatch = Batch::find($request->input('destination_batch_id'))->load('level', 'students');
        $currentStudentBatch = BatchStudent::where('student_id', $request->input('student_id'))->first()->load('batch.level');

        if ($destinationBatch->school_year_id !== SchoolYear::getActiveSchoolYear()->id) {
            return redirect()->back()->with('error', 'Batch is not active!');
        }

        // Check if the student is already in the batch
        $isDestinationBatchInvalid = BatchStudent::where([
            ['batch_id', $request->input('destination_batch_id')],
            ['student_id', $request->input('student_id')],
        ])->first();

        if ($isDestinationBatchInvalid) {
            return redirect()->back()->with('error', 'Student is already in the batch.');
        }

        // Check if batch_id is in the same level as the student's level
        if ($destinationBatch->level->id !== $currentStudentBatch->batch->level->id) {
            return redirect()->back()->with('error', 'Transferring to another grade is forbidden!');
        }

        // Check if the batch is already full
        $destinationBatchCapacity = $destinationBatch->max_students;

        $batchStudentsCount = $destinationBatch->students()->count();
        if ($batchStudentsCount >= $destinationBatchCapacity) {
            return redirect()->back()->with('error', "Grade {$destinationBatch->level->name} {$destinationBatch->section} is already full.");
        }

        $currentStudentBatch->update(['batch_id' => $destinationBatch->id]);

        $studentName = $currentStudentBatch->student->user->name;
        $batchName = $destinationBatch->level->name.$destinationBatch->section;

        return redirect()->back()->with('success', "{$studentName} has been transferred to {$batchName} successfully.");
    }
}

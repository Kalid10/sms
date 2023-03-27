<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\AssignStudentToBatchRequest;
use App\Models\BatchStudent;
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
}

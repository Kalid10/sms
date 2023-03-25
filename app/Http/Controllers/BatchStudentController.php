<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\AssignStudentToBatchRequest;
use App\Models\BatchStudent;
use Illuminate\Http\RedirectResponse;

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
}

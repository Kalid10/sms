<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\AssignHomeroomRequest;
use App\Models\Batch;
use App\Models\HomeroomTeacher;
use Illuminate\Http\RedirectResponse;

class TeacherController extends Controller
{
    public function assignHomeroomTeacher(AssignHomeroomRequest $request): RedirectResponse
    {
        // Get the teacher ID and batch ID from the request
        $teacherId = $request->teacher_id;
        $batchId = $request->batch_id;

        // If replace is true, delete the homeroom teacher
        if ($request->replace) {
            // Check if the teacher is assigned to only one batch
            if (HomeroomTeacher::where('teacher_id', $teacherId)->count() === 1) {
                HomeroomTeacher::where('teacher_id', $teacherId)->delete();
            } else {
                return redirect()->back()->withErrors(['teacher_id' => 'Cannot replace homeroom teacher. The teacher is assigned to more than one batch.']);
            }
        }

        Batch::findOrFail($batchId)->homeroomTeacher()->updateOrCreate(
            ['batch_id' => $batchId],
            ['teacher_id' => $teacherId]
        );

        return redirect()->back()->with('success', 'Homeroom teacher assigned successfully.');
    }

    public function removeHomeroomTeacher($id): RedirectResponse
    {
        // Find the homeroom teacher by ID or fail
        $homeroomTeacher = HomeroomTeacher::findOrFail($id);

        // Delete the homeroom teacher
        $homeroomTeacher->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Homeroom teacher removed successfully.');
    }
}

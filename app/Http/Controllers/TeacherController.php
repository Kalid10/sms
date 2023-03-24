<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\AssignHomeroomRequest;
use App\Models\Batch;
use App\Models\HomeroomTeacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

    public function getHomeroomTeachers(Request $request): Response
    {
        // Validate the request
        $request->validate([
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
        ]);

        // Get the teacher ID and batch ID from the request
        $teacherId = $request->teacher_id;
        $batchId = $request->batch_id;

        // Initialize the query builder for HomeroomTeacher
        $query = HomeroomTeacher::query();

        // If teacher ID is provided, filter by teacher ID
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        // If batch ID is provided, filter by batch ID
        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        // Load the related Teacher and Batch models, and paginate the results if no filters are applied
        $homerooms = $query->with(['teacher.user', 'batch.level'])->when(! $teacherId && ! $batchId, function ($query) {
            return $query->paginate(10);
        }, function ($query) {
            return $query->get();
        });

        // Return the results with the view
        return Inertia::render('Welcome', [
            'homerooms' => $homerooms,
        ]);
    }
}

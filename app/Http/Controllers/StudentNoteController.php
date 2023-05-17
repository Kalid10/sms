<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentNote;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNoteController extends Controller
{
    public function create(Request $request, $student_id): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->type == User::TYPE_ADMIN || $user->type == User::TYPE_TEACHER) {
            try {
                $student = Student::findOrFail($student_id);
            } catch (ModelNotFoundException $e) {
                return redirect()->back()->with('error', 'Invalid student ID');
            }

            // Check if the student is enrolled in the batch that the teacher is assigned to
            if ($user->type == User::TYPE_TEACHER) {
                $teacherBatches = $user->teacher->batchSubjects()->pluck('batch_id');
                if (! $teacherBatches->contains($student->activeBatch()->id)) {
                    return redirect()->back()->with('error', 'Unauthorized action');
                }
            }

            StudentNote::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'student_id' => $student_id,
                'user_id' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Student note created successfully');
        } else {
            return redirect()->back()->with('error', 'Unauthorized action');
        }
    }
}

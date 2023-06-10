<?php

namespace App\Http\Controllers\Web;

use App\Models\Student;
use App\Models\StudentNote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNoteController extends Controller
{
    public function create(Request $request, Student $student): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();

        if (! $user->type == User::TYPE_ADMIN || ! $user->type == User::TYPE_TEACHER) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Check if the student is enrolled in the batch that the teacher is assigned
        if ($user->type == User::TYPE_TEACHER) {
            $teacherBatches = $user->teacher->batchSubjects()->get()->filter(function ($batchSubject) {
                return $batchSubject->with('active');
            })->pluck('batch_id');

            if (! $teacherBatches->contains($student->activeBatch()->id)) {
                return redirect()->back()->with('error', 'Unauthorized action - student is not enrolled in your batch');
            }
        }

        StudentNote::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'student_id' => $student->id,
            'author_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Student note created successfully');
    }
}

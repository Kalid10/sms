<?php

namespace App\Http\Controllers;

use App\Models\BatchSession;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function addStudentNote(Request $request): RedirectResponse
    {
        $request->validate([
            'student_user_id' => 'required|exists:users,id',
            'body' => 'required',
            'title' => 'required',
            'batch_session_id' => 'nullable|exists:batch_sessions,id',
        ]);

        // Check if the user is student
        if (User::find($request->student_user_id)->user_type !== 'student') {
            return redirect()->back()->with('error', 'The user you are trying to add note to is not a student');
        }

        // Check the batch session access
        if ($request->batch_session_id) {
            $batchSession = BatchSession::find($request->batch_session_id);

            if ($batchSession->teacher_id !== Auth::user()->teacher->id) {
                return redirect()->back()->with('error', 'You dont have access to this class session');
            }

            if (! $batchSession->batchSchedule->batch->students->contains(User::find($request->student_user_id)->student->id)) {
                return redirect()->back()->with('error', "Student doesn't belong to this class");
            }
        }

        Note::create([
            'author_id' => auth()->id(),
            'entity_id' => $request->student_user_id,
            'title' => $request->title,
            'body' => $request->body,
            'batch_session_id' => $request->batch_session_id ?? null,
        ]);

        return redirect()->back()->with('success', 'Note added successfully');
    }
}

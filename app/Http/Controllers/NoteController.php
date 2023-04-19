<?php

namespace App\Http\Controllers;

use App\Models\BatchSession;
use App\Models\Note;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

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
        if (User::find($request->student_user_id)->type !== User::TYPE_STUDENT) {
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

    public function getStudentNotes(Request $request): Response
    {
        $request->validate([
            'student_user_id' => 'required|exists:users,id',
            'batch_session_id' => 'nullable|exists:batch_sessions,id',
            'batch_id' => 'nullable|exists:batches,id',
            'school_year_id' => 'nullable|exists:school_years,id',
            'author_user_id' => 'nullable|exists:users,id',
        ]);

        $schoolYearId = $request->school_year_id ?? SchoolYear::getActiveSchoolYear()->id;

        $notes = Note::where('entity_id', $request->student_user_id)
            ->when($request->batch_session_id, fn ($query) => $query->where('batch_session_id', $request->batch_session_id))
            ->when($request->batch_id, fn ($query) => $query->whereHas('batchSession.batchSchedule.batch', fn ($query) => $query->where('id', $request->batch_id)))
            ->when($request->author_user_id, fn ($query) => $query->where('author_id', $request->author_user_id))
            ->whereHas('batchSession.batchSchedule.batch', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->paginate(10);

        return Inertia::render('Welcome', [
            'notes' => $notes,
        ]);
    }
}

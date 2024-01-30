<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\StudentNoteRequest;
use App\Http\Resources\StudentNotes\Collection;
use App\Http\Resources\StudentNotes\Resource;
use App\Models\Student;
use App\Models\StudentNote;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StudentNoteController extends Controller
{
    public function index(Student $student)
    {
        // Get all notes for the student
        $notes = $student->notes->load('student.user', 'author.teacher');

        return new Collection($notes);
    }

    public function create(StudentNoteRequest $request, Student $student): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        StudentNote::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'student_id' => $student->id,
            'author_id' => Auth::user()->id,
        ]);

        return response([
            'message' => 'Student note created successfully',
        ], 201);
    }

    public function note(StudentNote $note): Resource
    {
        $note = $note->load('student.user', 'author.teacher');

        return new Resource($note);
    }

    public function updateNote(StudentNoteRequest $request, StudentNote $note): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        // Check if the logged-in user is the author of the note
        if ($note->author_id != Auth::user()->id) {
            return response([
                'message' => 'Unauthorized action - you are not the author of this note',
            ], 403);
        }

        $note->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response([
            'message' => 'Student note updated successfully',
        ], 200);
    }

    public function delete(StudentNote $note): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        // Check if the logged-in user is the author of the note
        if ($note->author_id != Auth::user()->id) {
            return response([
                'message' => 'Unauthorized action - you are not the author of this note',
            ], 403);
        }

        $note->delete();

        return response([
            'message' => 'Student note deleted successfully',
        ], 200);
    }
}

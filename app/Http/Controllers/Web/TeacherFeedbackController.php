<?php

namespace App\Http\Controllers\Web;

use App\Models\TeacherFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherFeedbackController extends Controller
{
    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'feedback' => 'required|string',
        ]);

        $author = auth()->user();

        // Check if author is not teacher
        if ($author->isTeacher()) {
            return redirect()->back()->with('error', 'You are not allowed to add feedback.');
        }

        TeacherFeedback::create([
            'teacher_id' => $request->teacher_id,
            'author_id' => $author->id,
            'feedback' => $request->feedback,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Feedback added successfully.');
    }

    public function update(Request $request, TeacherFeedback $feedback): RedirectResponse
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $author = auth()->user();

        // Check if author is not teacher
        if ($author->isTeacher()) {
            return redirect()->back()->with('error', 'You are not allowed to update feedback.');
        }

        $feedback->update([
            'feedback' => $request->feedback,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Feedback updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        TeacherFeedback::find($id)->delete();

        return redirect()->back()->with('success', 'Feedback deleted successfully.');
    }
}

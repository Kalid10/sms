<?php

namespace App\Http\Controllers\Web;

use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class QuestionsController extends Controller
{
    public function show(Request $request): Response
    {
        $request->validate([
            'user_id' => 'nullable|exits:users,id',
        ]);

        $userId = $request->user_id ?? auth()->user()->id;
        $questions = Question::where('user_id', $userId)->with('user', 'batchSubject.subject', 'assessmentType')->paginate(7);

        return Inertia::render('Teacher/Questions', [
            'questions' => $questions,
        ]);
    }
}

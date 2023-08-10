<?php

namespace App\Http\Controllers\Web;

use App\Jobs\QuestionGeneratorJob;
use App\Models\Question;
use App\Services\OpenAIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

        return Inertia::render('Teacher/Questions/Index', [
            'questions' => $questions,
        ]);
    }

    public function create(Request $request, OpenAIService $openAIService): RedirectResponse
    {
        $checkLimit = $openAIService->checkUsageLimit();
        if (! $checkLimit) {
            throw ValidationException::withMessages([
                'limit' => ['You have exceeded your daily usage limit.'],
            ]);
        }

        QuestionGeneratorJob::dispatch($request->all(), auth()->user()->id);

        return redirect()->back()->with('success', 'You have successfully started generating questions, we will notify you once we are done');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'index' => 'nullable|integer|min:0',
        ]);

        $question = Question::find($request->question_id);

        $questions = $question->questions;

        if (isset($questions[$request->index])) {
            $questions[$request->index]['question'] = $request->question;
            $questions[$request->index]['answer'] = $request->answer;
            $questions[$request->index]['difficulty'] = $request->answer;
            $questions[$request->index]['question_type'] = $request->question_type;

            $question->update(['questions' => $questions]);

            return redirect()->back()->with('success', 'Question updated successfully.');
        }

        return redirect()->back()->with('error', 'Unable to update question. Index not found.');
    }

    public function delete(Request $request): RedirectResponse
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'index' => 'nullable|integer|min:0',
        ]);

        $question = Question::find($request->question_id);

        if (! $request->index) {
            $question->delete();
        } else {
            $questions = $question->questions;

            array_splice($questions, $request->index, 1);

            $question->update(['questions' => $questions]);
        }

        return redirect()->back()->with('success', 'Question deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    public function index(): Response
    {
        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Books',
            User::TYPE_ADMIN => 'Admin/Books/Index',
            default => throw new Exception('Type unknown!'),
        };

        // TODO: Check is user is teacher and fetch books for his/her batches only
        return Inertia::render($page, [
            'books' => Book::paginate(10),
        ]);
    }

    public function showAddBookForm(): Response
    {
        return Inertia::render('Admin/Books/Add', [
            'levels' => Level::all(),
            'subjects' => Subject::all(),
        ]);
    }

    public function showAddBockChapterForm(Request $request): Response
    {
        Log::info("Request: $request");
        $request->validate([
            'title' => 'required|string|exists:books,title',
            'level_id' => 'required|exists:levels,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Get book
        $book = Book::where('title', $request->title)
            ->where('level_id', $request->level_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        Log::info("Book: $book");

        return Inertia::render('Admin/Books/Add/Chapter', [
            'book' => $book,
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string',
            'level_id' => 'required|exists:levels,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        Book::create($request->all());

        return redirect()->back()->with('success', 'Book created successfully!');
    }
}

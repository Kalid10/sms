<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return Inertia::render('Admin/Books/Add/Basic', [
            'levels' => Level::all(),
            'subjects' => Subject::all(),
        ]);
    }

    public function showAddChapters(Book $book): Response
    {
        return Inertia::render('Admin/Books/Add/Chapter', [
            'book' => $book->load('chapters'),

        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|unique:books',
            'level_id' => 'required|exists:levels,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $book = Book::create($request->all());

        return redirect()->to('/admin/books/'.$book->id.'/chapter');
    }

    public function createChapter(Book $book, Request $request): RedirectResponse
    {

        $request->validate([
            'title' => 'required|string',
            'summary' => 'string',
            'start_page' => 'required|integer|lte:end_page',
            'end_page' => 'required|integer|gte:start_page',
        ]);

        // Check if there is no similar chapter in this book
        if ($book->chapters()->where('title', $request->title)->exists()) {
            return redirect()->back()->with('error', 'Chapter with this title already exists in this book.');
        }

        // Check if the start_page and end_page are not included on any other chapter
        if ($book->chapters()->where('start_page', '>=', $request->start_page)->exists()) {
            return redirect()->back()->withErrors(['start_page' => 'Invalid page, this page is already included on other chapter!']);
        }

        if ($book->chapters()->where('end_page', '>=', $request->end_page)->exists()) {
            return redirect()->back()->withErrors(['end_page' => 'Invalid page, this page is already included on other chapter!']);
        }

        $book->chapters()->create($request->all());

        return redirect()->back()->with('success', 'Chapter added successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\CreateSubjectRequest;
use App\Http\Requests\Subjects\UpdateSubjectRequest;
use App\Models\Subject;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        // Get search key
        $searchKey = $request->input('search');

        Log::info('Search key: '.$searchKey);

        // Get subjects
        $subjects = Subject::select('full_name', 'short_name', 'id')->where('full_name', 'like', '%'.$searchKey.'%')->paginate(10);

        return Inertia::render('Subject/Index', [
            'subjects' => $subjects,
        ]);
    }

    public function create(CreateSubjectRequest $request): RedirectResponse
    {
        try {
            Subject::create([
                'full_name' => $request->full_name,
                'short_name' => $request->short_name,
            ]);

            return redirect()->back()->with('success', $request->full_name.' added successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(UpdateSubjectRequest $request): RedirectResponse
    {
        try {
            Subject::find($request->id)->update([
                'full_name' => $request->full_name,
                'short_name' => $request->short_name,
            ]);

            return redirect()->back()->with('success', $request->full_name.' updated successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

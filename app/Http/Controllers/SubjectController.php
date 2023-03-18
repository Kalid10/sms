<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\CreateRequest;
use App\Http\Requests\Subjects\UpdateRequest;
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

        // Get subjects
        $subjects = Subject::select('id', 'full_name', 'short_name')->where('full_name', 'like', '%'.$searchKey.'%')->paginate(10);

        return Inertia::render('Subject/Index', [
            'subjects' => $subjects,
        ]);
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        try {
            Subject::create([
                'full_name' => $request->full_name,
                'short_name' => $request->short_name,
            ]);

            return  redirect()->back()->with('success', $request->full_name.' added successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(UpdateRequest $request): RedirectResponse
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

    public function delete($id): RedirectResponse
    {
        try {
            // Check if subject exists
            $subject = Subject::find($id);

            if (! $subject) {
                return redirect()->back()->with('error', 'Subject not found.');
            }

            // Delete subject
            $subject->delete();

            return redirect()->back()->with('success', 'Subject deleted successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

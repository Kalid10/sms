<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\CreateBulkRequest;
use App\Http\Requests\Subjects\CreateRequest;
use App\Http\Requests\Subjects\UpdateRequest;
use App\Models\Subject;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SubjectController extends Controller
{
    public function index(Request $request): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // Get subjects
        $subjects = Subject::select('id', 'full_name', 'short_name', 'category', 'tags')->where('full_name', 'like', '%'.$searchKey.'%')
            ->OrWhere(DB::raw('lower(tags)'), 'like', '%'.$searchKey.'%')->paginate(10);

        // Get deleted subjects with search key
        $deletedSubjects = Subject::onlyTrashed()->select('id', 'full_name', 'short_name')->where('full_name', 'like', '%'.$searchKey.'%')->paginate(10);

        return Inertia::render('Subjects/Index', [
            'subjects' => $subjects,
            'deletedSubjects' => $deletedSubjects,
        ]);
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        try {
            // Check if subject is deleted
            $subject = Subject::withTrashed()->where('full_name', $request->full_name)->first();

            if (! $subject) {
                Subject::create([
                    'full_name' => $request->full_name,
                    'short_name' => $request->short_name,
                    'category' => $request->category,
                    'tags' => $request->tags,
                ]);
            } else {
                return redirect()->back()->with('error', 'Subject already exists.');
            }

            return redirect()->back()->with('success', $request->full_name.' added successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function createBulk(CreateBulkRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->subjects as $subject) {
                Subject::create([
                    'full_name' => $subject['full_name'],
                    'short_name' => $subject['short_name'],
                    'category' => $subject['category'],
                    'tags' => $subject['tags'],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Subjects added successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        try {
            Subject::find($request->id)->update([
                'full_name' => $request->full_name,
                'short_name' => $request->short_name,
                'category' => $request->category,
                'tags' => $request->tags,
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

            // Check if subject is associated with teacher or batch
            if ($subject->teachers || $subject->batch) {
                // Delete subject with soft delete
                $subject->delete();
            }

            // Delete subject permanently
            $subject->forceDelete();

            return redirect()->back()->with('success', 'Subject deleted successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // Restore subject
    public function restore($id): RedirectResponse
    {
        try {
            // Check if subject exists
            $subject = Subject::withTrashed()->find($id);

            if (! $subject) {
                return redirect()->back()->with('error', 'Subject not found.');
            }

            // Restore subject
            $subject->restore();

            return redirect()->back()->with('success', 'Subject restored successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

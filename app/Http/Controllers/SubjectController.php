<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\CreateRequest;
use App\Http\Requests\Subjects\UpdateRequest;
use App\Models\Subject;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
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

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
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

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
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

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

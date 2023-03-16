<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\CreateSubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    // Add function to create a new subject, use try and catch and after adding return inertia view
    public function create(CreateSubjectRequest $request)
    {
        try {
            Subject::create([
                'name' => $request->name,
            ]);

            return  redirect()->back()->with('success', $request->name.' added successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

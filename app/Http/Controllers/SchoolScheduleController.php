<?php

namespace App\Http\Controllers;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SchoolScheduleController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'nullable',
            'start_date' => 'required|date|before:end_date|after_or_equal:today',
            'end_date' => 'required|date',
            'type' => 'required',
            'school_year_id' => 'required|exists:school_years,id|integer|gt:0',
        ]);

        // Check if school year is active
        if (! isset(SchoolYear::find($validated['school_year_id'])->end_date)) {
            return redirect()->back()->withErrors(['school_year_id', 'The school year is not active.']);
        }

        SchoolSchedule::create($validated);

        return redirect()->back()->with('success', $validated['title'].' has been added to the schedule.');
    }
}

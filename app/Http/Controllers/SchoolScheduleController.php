<?php

namespace App\Http\Controllers;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

    public function list(Request $request): RedirectResponse|Response
    {
        $request->validate([
            'school_year_id' => 'nullable|exists:school_years,id|integer|gt:0',
        ]);

        $schoolYearId = $request->input('school_year_id') ?? SchoolYear::whereNull('end_date')->first()->id;

        $schoolSchedule = SchoolSchedule::query()
            ->where('school_year_id', $schoolYearId)
            ->when($request->input('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->input('start_date'), function ($query, $startDate) {
                return $query->where('start_date', '>=', $startDate);
            })
            ->when($request->input('end_date'), function ($query, $endDate) {
                return $query->where('end_date', '<=', $endDate);
            })
            ->get();

        return Inertia::render('Welcome', ['school_schedules' => $schoolSchedule]);
    }
}

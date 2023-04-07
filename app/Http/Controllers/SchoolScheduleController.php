<?php

namespace App\Http\Controllers;

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SchoolScheduleController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        // Get the active school year and create the school schedule
        $schoolYear = SchoolYear::getActiveSchoolYear();

        if (! $schoolYear) {
            return redirect()->back()->with('error', 'No active school year found.');
        }

        $schoolSchedule = SchoolSchedule::make($validated);
        $schoolSchedule->schoolYear()->associate($schoolYear);
        $schoolSchedule->save();

        return redirect()->back()->with('success', $validated['title'].' has been added to the schedule.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        // Check if the school schedule is not in the active school year
        $schoolSchedule = SchoolSchedule::find($request->id);
        if ($schoolSchedule->schoolYear->end_date) {
            return redirect()->back()->with('error', 'Cannot update a school schedule that is not in the active school year.');
        }

        SchoolSchedule::find($request->id)->update($validated);

        return redirect()->back()->with('success', $validated['title'].' has been updated.');
    }

    public function list(Request $request): RedirectResponse|Response
    {
        $request->validate([
            'school_year_id' => 'nullable|exists:school_years,id|integer|gt:0',
        ]);

        $schoolYearId = $request->input('school_year_id') ?? SchoolYear::getActiveSchoolYear()->id;

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

    public function delete($id): RedirectResponse
    {
        // Check if the school schedule exists
        if (! SchoolSchedule::find($id)) {
            return redirect()->back()->withErrors(['id' => 'The school schedule does not exist.']);
        }

        // Check if the school schedule is not in the past
        if (Carbon::parse(SchoolSchedule::find($id)->start_date)->isPast()) {
            return redirect()->back()->withErrors(['id' => 'Can not delete, the school schedule is in the past.']);
        }
        SchoolSchedule::find($id)->delete();

        return redirect()->back()->with('success', 'Schedule has been deleted successfully.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable',
            'start_date' => 'required|date|before:end_date|after_or_equal:today',
            'end_date' => 'required|date',
            'type' => 'required|in:closed,half_closed,not_closed',
            'tags' => 'nullable|array',
            'id' => $request->id ? 'sometimes|exists:school_schedules,id|integer|gt:0' : '',
        ]);
    }
}

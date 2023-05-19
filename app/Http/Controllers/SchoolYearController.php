<?php

namespace App\Http\Controllers;

use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolYearController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'number_of_semesters' => 'required|integer|min:1',
            'name' => 'required|string',
            'number_of_quarters' => 'required|integer|min:1',
        ]);

        // Check if there is an ongoing academic year
        if (SchoolYear::getActiveSchoolYear()) {
            return redirect()->back()->with('error', 'The current academic year is ongoing. You cannot start a new academic year without ending the current one.');
        }

        // Start a new transaction
        DB::beginTransaction();

        try {
            $startDate = Carbon::parse($request->input('start_date'));
            $startDate = $startDate->format('Y-m-d');
            $schoolYear = SchoolYear::create([
                'start_date' => $startDate,
                'end_date' => null,
                'name' => $request->name,
            ]);

            // Create semester records
            for ($i = 1; $i <= $request->number_of_semesters; $i++) {
                Semester::create([
                    'school_year_id' => $schoolYear->id,
                    'name' => "Semester {$i}",
                    'start_date' => $i === 1 ? $startDate : null,
                    'end_date' => null,
                    'status' => $i === 1 ? Semester::STATUS_ACTIVE : Semester::STATUS_UPCOMING,
                ]);
            }

            $semesters = $schoolYear->semesters;

            foreach ($semesters as $semester) {
                for ($i = 1; $i <= $request->number_of_quarters; $i++) {
                    Quarter::create([
                        'semester_id' => $semester->id,
                        'name' => "Quarter {$i}",
                        'start_date' => $i === 1 ? $semester->start_date : null,
                        'end_date' => null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'School year successfully created.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}

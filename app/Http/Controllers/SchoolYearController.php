<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Semester;
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
        ]);

        // Check if there is an ongoing academic year
        $ongoingSchoolYear = SchoolYear::whereNull('end_date')->first();
        if ($ongoingSchoolYear) {
            return redirect()->back()->with('error', 'The current academic year is ongoing. You cannot start a new academic year without ending the current one.');
        }

        // Start a new transaction
        DB::beginTransaction();

        try {
            $schoolYear = SchoolYear::create([
                'start_date' => $request->start_date,
                'end_date' => null,
                'name' => $request->name,
            ]);

            // Create semester records
            for ($i = 1; $i <= $request->number_of_semesters; $i++) {
                Semester::create([
                    'school_year_id' => $schoolYear->id,
                    'name' => "Semester {$i}",
                    'start_date' => $i === 1 ? $request->start_date : null,
                    'end_date' => null,
                    'status' => $i === 1 ? Semester::STATUS_ACTIVE : Semester::STATUS_UPCOMING,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'School year created successfully with.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}

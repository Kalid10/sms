<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolYearController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'date|nullable|after:start_date',
        ]);

        // Check if there is a ongoing academic year
        $schoolYear = SchoolYear::whereNull('end_date')->first();
        if ($schoolYear) {
            return redirect()->back()->with('error', 'The current academic year is ongoing. You cannot start a new academic year without ending the current one.');
        }

        try {
            SchoolYear::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->back()->with('success', 'School year created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}

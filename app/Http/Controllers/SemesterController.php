<?php

namespace App\Http\Controllers;

use App\Http\Requests\Semesters\CreateRequest;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterController extends Controller
{
    public function create(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $semesters = $request->get('semesters');

            foreach ($semesters as $semester) {
                Semester::create(array_merge($semester, ['school_year_id' => SchoolYear::where('end_date', null)->first()->id]));
            }

            DB::commit();

            return redirect()->back()->with('success', 'Semesters created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

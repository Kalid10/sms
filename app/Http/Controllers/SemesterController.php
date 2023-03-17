<?php

namespace App\Http\Controllers;

use App\Http\Requests\Semesters\CreateRequest;
use App\Http\Requests\Semesters\UpdateRequest;
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

    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            Semester::find($request->id)->update($request->validated());

            DB::commit();

            return redirect()->back()->with('success', 'Semester updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Semesters\CreateRequest;
use App\Http\Requests\Semesters\UpdateRequest;
use App\Models\SchoolYear;
use App\Models\Semester;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SemesterController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Semesters/Index');
    }

    public function show(): Response
    {
        return Inertia::render('Semesters/Single');
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $semesters = $request->get('semesters');

            foreach ($semesters as $semester) {
                Semester::create(array_merge($semester, ['school_year_id' => SchoolYear::where('end_date', null)->first()->id]));
            }

            DB::commit();

            return redirect()->back()->with('success', 'Semesters created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            Semester::find($request->id)->update($request->validated());

            DB::commit();

            return redirect()->back()->with('success', 'Semester updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function list(): RedirectResponse|Response
    {
        try {
            // Get all semesters
            $semesters = Semester::all();

            // Get active school year semesters
            $activeSchoolYearSemesters = $semesters->filter(function ($semester) {
                return $semester->schoolYear->end_date === null;
            });

            // Get active semester
            $activeSemester = $semesters->filter(function ($semester) {
                return $semester->status === Semester::STATUS_ACTIVE;
            });

            // Count of semesters
            $semestersCount = Semester::count();

            // Count of all school years
            $schoolYearsCount = SchoolYear::count();

            // Count active school year semesters
            $activeSchoolYearSemestersCount = $activeSchoolYearSemesters->count();

            // TODO: Change to the correct component
            return Inertia::render('Semesters/Index', [
                'semesters' => $semesters,
                'activeSemester' => $activeSemester,
                'activeSchoolYearSemesters' => $activeSchoolYearSemesters,
                'semestersCount' => $semestersCount,
                'schoolYearsCount' => $schoolYearsCount,
                'activeSchoolYearSemestersCount' => $activeSchoolYearSemestersCount,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            // Check if semester exists
            $semester = Semester::find($id);

            if (! $semester) {
                return redirect()->back()->with('error', 'Semester not found.');
            }

            // Check if semester is upcoming
            if ($semester->status !== Semester::STATUS_UPCOMING) {
                return redirect()->back()->with('error', 'Can only delete upcoming semesters.');
            }

            // Delete semester
            $semester->delete();

            redirect()->back()->with('success', 'Semester deleted successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}

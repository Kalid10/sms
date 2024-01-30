<?php

namespace App\Http\Controllers\Web;

use App\Imports\BatchScheduleImport;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\SchoolPeriod;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\BatchSubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class GettingStartedController extends Controller
{
    public function index(Request $request): Response
    {
        $step = 1;

        if (SchoolYear::getActiveSchoolYear() !== null) {
            $step = $step + 1;

            if (Batch::active()->count() > 1) {
                $step = $step + 1;
            }
        }

        if (Subject::count() > 1) {
            $step++;
        }

        if (BatchSubject::count() > 0) {
            $step++;
        }

        if (Teacher::count() > 0) {
            $step++;
        }

        $levelCategories = Inertia::lazy(fn () => LevelCategory::whereHas('levels.batches', function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()?->id);
        })->with(['levels' => function ($query) {
            $query->whereHas('batches', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            });
        }])->get());

        return Inertia::render('Admin/GettingStarted/Index', [
            'step' => $step,
            'levels' => Level::with('batches', 'levelCategory')->get(),
            'batches' => Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()?->id)->with('level.levelCategory', 'subjects.subject')->get(),
            'subjects' => Inertia::lazy(fn () => Subject::all()),
            'level_categories' => $levelCategories,
            'school_periods' => SchoolPeriod::with('levelCategory')->get(),
            'school_schedule' => SchoolSchedule::all(),
            'batchSubjects' => $request->input('batch_id') ? $this->getBatchSubjects($request->input('batch_id')) : [],
            'teachers' => Inertia::lazy(function () use ($request) {
                return BatchSubjectService::searchTeachers($request);
            }),
        ]);
    }

    public function schoolSchedule(): Response
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();

        $schoolYear->is_ready = true;
        $schoolYear->save();

        return Inertia::render('Admin/GettingStarted/SchoolSchedule', [
            'school_schedule' => SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get(),
        ]);
    }

    public function schoolPeriod(): Response
    {
        return Inertia::render('Admin/GettingStarted/SchoolPeriod', [
            'level_categories' => Inertia::lazy(fn () => LevelCategory::all()),
        ]);
    }

    public function classSchedule(): Response
    {
        return Inertia::render('Admin/GettingStarted/ClassSchedule', [
            'school_schedule' => SchoolSchedule::all(),
        ]);
    }

    public function batchSchedule(): Response
    {
        return Inertia::render('Admin/GettingStarted/BatchSchedule');
    }

    public function finish(): RedirectResponse
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();
        $schoolYear->is_ready = true;
        $schoolYear->save();

        return redirect()->to('/admin');
    }

    public function batchScheduleImport(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Start the import queue
        Excel::queueImport(new BatchScheduleImport(), $request->file('file'));

        return redirect()->back()->with('success', 'Batch schedules imported successfully.');
    }

    private function getBatchSubjects($batchId): Collection
    {

        if (! $batchId) {
            return collect();
        }

        $batch = Batch::find($batchId);

        return $batch->load('subjects.subject', 'subjects.teacher.user')->subjects->sortBy('priority');
    }

    public function updateBatchSubjects(Request $request): void
    {
        BatchSubjectService::update($request);
    }
}

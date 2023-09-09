<?php

namespace App\Http\Controllers\Web;

use App\Models\Batch;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\SchoolPeriod;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use Inertia\Inertia;
use Inertia\Response;

class GettingStartedController extends Controller
{
    public function index(): Response
    {
        $step = 1;

        if (SchoolYear::getActiveSchoolYear() !== null) {
            $step = $step + 1;

            if (Batch::active()->count() > 1) {
                $step = $step + 1;
            }
        }

        if (Subject::count() > 1) {
            $step = $step + 1;
        }

        $levelCategories = Inertia::lazy(fn () => LevelCategory::whereHas('levels.batches', function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
        })->with(['levels' => function ($query) {
            $query->whereHas('batches', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            });
        }])->get());

        return Inertia::render('Admin/GettingStarted/Index', [
            'step' => $step,
            'levels' => Level::with('batches', 'levelCategory')->get(),
            'batches' => Inertia::lazy(fn () => Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->with('level.levelCategory', 'subjects.subject')->get()),
            'subjects' => Inertia::lazy(fn () => Subject::all()),
            'level_categories' => $levelCategories,
            'school_periods' => SchoolPeriod::with('levelCategory')->get(),
            'school_schedule' => SchoolSchedule::all(),
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

    public function finish()
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();
        $schoolYear->is_ready = true;
        $schoolYear->save();

        return redirect()->to('/admin');
    }
}

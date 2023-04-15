<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Level;
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

        return Inertia::render('GettingStarted/Index', [
            'step' => $step,
            'levels' => Level::all(),
            'batches' => Inertia::lazy(fn () => Batch::active(['level'])),
            'subjects' => Inertia::lazy(fn () => Subject::all()),
        ]);
    }

    public function schoolSchedule(): Response
    {
        return Inertia::render('GettingStarted/SchoolSchedule', [
            'school_schedule' => SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get(),
        ]);
    }
}

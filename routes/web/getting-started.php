<?php

use App\Models\Batch;
use App\Models\Level;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('getting-started')->group(function () {
    Route::get('/', function () {
        return Inertia::render('GettingStarted/RegisterSchoolYear');
    });
    Route::get('/register-admin', function () {
        return Inertia::render('GettingStarted/RegisterAdmin');
    });
    Route::get('/school-schedule', function () {
        return Inertia::render('GettingStarted/SchoolSchedule', [
            'school_schedule' => SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get(),
        ]);
    });
    Route::get('/register-batches', function () {
        return Inertia::render('GettingStarted/RegisterBatches', [
            'levels' => Level::all(),
        ]);
    });
    Route::get('/register-subjects', function () {
        return Inertia::render('GettingStarted/RegisterSubjects');
    });

    Route::get('/assign-subjects', function () {
        return Inertia::render('GettingStarted/AssignSubjects', [
            'subjects' => Subject::all(),
            'batches' => Batch::active(['level']),
        ]);
    });
});

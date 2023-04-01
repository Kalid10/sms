<?php

use App\Models\Level;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('getting-started')->group(function () {
    Route::get('/register-admin', function () {
        return Inertia::render('GettingStarted/RegisterAdmin');
    });
    Route::get('/register-year', function () {
        return Inertia::render('GettingStarted/RegisterSchoolYear');
    });
    Route::get('/register-batches', function () {
        return Inertia::render('GettingStarted/RegisterBatches', [
            'levels' => Level::all(),
        ]);
    });
    Route::get('/register-subject', function () {
        return Inertia::render('GettingStarted/RegisterSubject');
    });
});
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('getting-started')->group(function () {
    Route::get('/register-admin', function () {
        return Inertia::render('GettingStarted/RegisterAdmin');
    });
});

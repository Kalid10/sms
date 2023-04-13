<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('/students')->middleware([])->name('students.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('/Students/Index');
    })->name('index');

    Route::get('/{id}', function () {
        return Inertia::render('Students/Single');
    });
});

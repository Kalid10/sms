<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/users', function () {
    return Inertia::render('Users/Index');
});

// UI test routes
Route::get('/registration', function () {
    return Inertia::render('Users/UserRegistration');
});
Route::get('/year-registration', function () {
    return Inertia::render('Users/SchoolYearRegistration');
});
Route::get('/batch-registration', function () {
    return Inertia::render('Users/BatchRegistration');
});

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/users', function () {
    return Inertia::render('Users/Index');
});

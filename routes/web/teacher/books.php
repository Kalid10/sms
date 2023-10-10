<?php

use App\Http\Controllers\Web\BookController;
use Illuminate\Support\Facades\Route;

Route::controller(BookController::class)->prefix('teacher/books/')->middleware(['auth'])->name('teacher.books')->group(function () {
    Route::get('', 'index')->name('index');
});

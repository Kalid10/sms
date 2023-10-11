<?php

use App\Http\Controllers\Web\BookController;
use Illuminate\Support\Facades\Route;

Route::controller(BookController::class)->prefix('admin/books/')->middleware(['auth', 'checkUserRole:manage-books'])->name('admin.books')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('add', 'showAddBookForm')->name('add');
    Route::get('{book}/chapter', 'showAddChapters')->name('add.chapter');
    Route::post('create', 'create')->name('create');
    Route::post('{book}/chapter/create', 'createChapter')->name('create.chapter');
});

<?php

use App\Http\Controllers\Web\BookController;
use Illuminate\Support\Facades\Route;

Route::controller(BookController::class)->prefix('admin/books/')->middleware(['auth', 'checkUserRole:manage-books'])->name('admin.books')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('add', 'showAddBook')->name('add');
    Route::get('{book}/chapter', 'showAddChapters')->name('add.chapter');
    Route::get('{book}/upload-book-cover', 'showUploadBookCover')->name('upload.book.cover');
    Route::post('create', 'create')->name('create');
    Route::post('{book}/chapter/create', 'createChapter')->name('create.chapter');
    Route::post('{book}/upload-book-cover', 'uploadBookCover')->name('upload.book.cover');
});

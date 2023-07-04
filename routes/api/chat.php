<?php

use App\Http\Controllers\API\MessagesController;
use Illuminate\Support\Facades\Route;

Route::controller(MessagesController::class)->prefix('chat/')->middleware(['web', 'auth', 'api'])->name('chat.')->group(function () {
    Route::get('{id}', 'index')->name('index');
});

<?php

use App\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Route;

Route::controller(SMSController::class)->prefix('sms/')->name('sms.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('/send', 'send')->name('send');
});

<?php

use App\Http\Controllers\Web\LocaleController;
use Illuminate\Support\Facades\Route;

Route::post('/set-locale/{locale}', [LocaleController::class, 'setLocale']);

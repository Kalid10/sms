<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Add routes from web folder
foreach (glob(__DIR__.'/web/*.php') as $file) {
    require $file;
}
Route::post('/assign-role', [RoleController::class, 'assignRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('assign-role');
Route::post('/remove-role', [RoleController::class, 'removeRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('remove-role');

Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('password.request');

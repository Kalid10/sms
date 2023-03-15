<?php

use App\Http\Controllers\Auth\LoginController;
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

Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login');
Route::post('/assign-role', [RoleController::class, 'assignRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('assign-role');
Route::post('/remove-role', [RoleController::class, 'removeRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('remove-role');

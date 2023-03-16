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
Route::get('/users', function () {
    return Inertia::render('Users/Index');
});

Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login');
Route::post('/assign-role', [RoleController::class, 'assignRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('assign-role');
Route::post('/remove-role', [RoleController::class, 'removeRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('remove-role');
Route::get('/login', [LoginController::class, 'create'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

//Route::get('/login', function () {
//    return Inertia::render('Auth/Login');
//})->name('login');

Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('password.request');

Route::get('/subject', function () {
    return Inertia::render('Subject/Index');
})->name('subject.index');

Route::get('/subject-add', function () {
    return Inertia::render('Subject/Add');
})->name('subject-add');

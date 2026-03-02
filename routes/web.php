<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing_page');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register.page');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login.page');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::resource('user', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('returns', ReturnController::class);
    Route::resource('loans', LoanController::class);
    Route::get('/loans/index-table', [LoanController::class, 'show'])->name('loans.index-table');
    Route::post('/loans/{id}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{id}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::post('/loans/{id}/borrowed', [LoanController::class, 'borrowed'])->name('loans.borrowed');
    Route::post('/loans/{id}/complete', [LoanController::class, 'complete'])->name('loans.complete');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
});


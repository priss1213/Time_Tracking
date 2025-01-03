<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeRecordController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

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
Route::get('/login', [TimeRecordController::class, 'showLoginForm'])->name('login');
Route::post('/login', [TimeRecordController::class, 'processLogin']);
Route::post('/logout', [TimeRecordController::class, 'logout'])->name('logout');

Route::get('/dashboard', [TimeRecordController::class, 'showDashboard'])->name('dashboard');
Route::get('/dashboard', [TimeRecordController::class, 'index'])->name('dashboard');
Route::post('/clock-in', [TimeRecordController::class, 'clockIn'])->name('clock.in');
Route::post('/clock-out', [TimeRecordController::class, 'clockOut'])->name('clock.out');
Route::delete('/delete-account', [TimeRecordController::class, 'deleteAccount'])->name('account.delete');



Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

Route::post('/break/start', [TimeRecordController::class, 'startBreak'])->name('break.start');
Route::post('/break/end', [TimeRecordController::class, 'endBreak'])->name('break.end');
// Route::post('/start-break', [TimeRecordController::class, 'startBreak'])->name('start.break');
// Route::post('/end-break', [TimeRecordController::class, 'endBreak'])->name('end.break');












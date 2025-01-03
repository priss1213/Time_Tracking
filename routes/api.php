<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth'])->group(function () {
    Route::get('/api/current-status', [TimeRecordController::class, 'getCurrentStatus']);
    Route::post('/api/clock-in', [TimeRecordController::class, 'clockIn']);
    Route::post('/api/start-break', [TimeRecordController::class, 'startBreak']);
    Route::post('/api/end-break', [TimeRecordController::class, 'endBreak']);
    Route::post('/api/clock-out', [TimeRecordController::class, 'clockOut']);
});


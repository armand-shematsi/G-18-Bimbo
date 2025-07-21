<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffAvailabilityController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Example:
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// }); 

Route::get('/users', [App\Http\Controllers\ApiUserController::class, 'index']);
Route::get('/shifts', [App\Http\Controllers\ShiftController::class, 'index']);
Route::get('/staff-on-duty', [App\Http\Controllers\ApiUserController::class, 'staffOnDuty']);
Route::get('/staff-availability', [StaffAvailabilityController::class, 'index']);
Route::post('/attendance', [AttendanceController::class, 'update']);
Route::get('/workforce-analytics', [App\Http\Controllers\ApiUserController::class, 'workforceAnalytics']);
Route::get('/production-lines', [App\Http\Controllers\ProductionLineController::class, 'index']);
Route::get('/production-live', [App\Http\Controllers\DashboardController::class, 'productionLive']);
Route::get('/production-activity', [App\Http\Controllers\DashboardController::class, 'notificationsLive']);
Route::get('/production-trends', [\App\Http\Controllers\Bakery\ProductionController::class, 'trends']);
Route::get('/production-batch-output-trends', [\App\Http\Controllers\Bakery\ProductionController::class, 'batchOutputTrends']);
Route::get('/production-batch-output-trends-week', [\App\Http\Controllers\Bakery\ProductionController::class, 'batchOutputTrendsWeek']);
Route::get('/batches/{batch}/shifts', function ($batchId) {
    $batch = \App\Models\ProductionBatch::with(['shifts.user'])->findOrFail($batchId);
    return response()->json(
        $batch->shifts->map(function ($shift) {
            return [
                'id' => $shift->id,
                'user_name' => $shift->user ? $shift->user->name : 'Unassigned',
                'role' => $shift->role,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ];
        })
    );
});

// Add API resource routes for workforce management
Route::get('/assignments/filled-count', [App\Http\Controllers\Api\AssignmentController::class, 'filledCount']);
Route::apiResource('staff', App\Http\Controllers\Api\StaffController::class);
Route::apiResource('supply-centers', App\Http\Controllers\Api\SupplyCenterController::class);
Route::apiResource('assignments', App\Http\Controllers\Api\AssignmentController::class);

// Staff status update routes
Route::put('/staff/{id}/status', [App\Http\Controllers\Api\StaffController::class, 'updateStatus']);
Route::get('/staff-on-duty-count', [App\Http\Controllers\Api\StaffController::class, 'getStaffOnDuty']);
Route::get('/production-overview', [\App\Http\Controllers\Bakery\DashboardController::class, 'productionOverview']);
Route::get('/recent-activity', [\App\Http\Controllers\Bakery\DashboardController::class, 'recentActivity']);
Route::get('/notifications-live', [App\Http\Controllers\DashboardController::class, 'notificationsLive']);

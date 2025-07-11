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
Route::get('/machines-live', [App\Http\Controllers\DashboardController::class, 'machinesLive']);
Route::get('/production-activity', [App\Http\Controllers\DashboardController::class, 'notificationsLive']);
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

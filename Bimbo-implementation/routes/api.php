<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/shifts', [App\Http\Controllers\ApiShiftController::class, 'index']);
Route::get('/staff-on-duty', [App\Http\Controllers\ApiUserController::class, 'staffOnDuty']);
Route::get('/staff-availability', [App\Http\Controllers\ApiUserController::class, 'staffAvailability']);
Route::get('/workforce-analytics', [App\Http\Controllers\ApiUserController::class, 'workforceAnalytics']);
Route::get('/production-lines', [App\Http\Controllers\ProductionLineController::class, 'index']);

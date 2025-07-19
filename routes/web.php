<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::middleware(['auth', 'role:retail_manager'])->prefix('retail')->name('retail.')->group(function () {
    Route::get('/inventory/create', [\App\Http\Controllers\Retail\InventoryController::class, 'create'])->name('inventory.create');
});

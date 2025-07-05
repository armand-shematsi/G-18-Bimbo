<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Supplier\InventoryController;
use App\Http\Controllers\Supplier\OrderController;
use App\Http\Controllers\Supplier\ChatController;
use App\Http\Controllers\Bakery\ProductionController;
use App\Http\Controllers\Bakery\ScheduleController;
use App\Http\Controllers\Bakery\MaintenanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkforceController;
use App\Http\Controllers\OrderReturnController;
use App\Http\Controllers\SupportRequestController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('vendors', VendorController::class);
        Route::resource('users', UserController::class);
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
Route::get('/analytics/sales-predictions', [AnalyticsController::class, 'salesPredictions'])->name('analytics.sales_predictions');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        // Dashboard summary API endpoints
        Route::get('/api/dashboard/production-summary', [\App\Http\Controllers\Admin\DashboardController::class, 'productionSummary'])->name('dashboard.production-summary');
        Route::get('/api/dashboard/staff-summary', [\App\Http\Controllers\Admin\DashboardController::class, 'staffSummary'])->name('dashboard.staff-summary');
        Route::get('/api/dashboard/maintenance-summary', [\App\Http\Controllers\Admin\DashboardController::class, 'maintenanceSummary'])->name('dashboard.maintenance-summary');
        // Workforce distribution API endpoint
        Route::get('/api/workforce-distribution', [\App\Http\Controllers\DashboardController::class, 'workforceDistribution'])->name('workforce.distribution.api');
        Route::get('/customer-segments', [\App\Http\Controllers\CustomerSegmentController::class, 'index'])->name('customer-segments');
    });

    // Supplier routes
    Route::prefix('supplier')->name('supplier.')->middleware('role:supplier')->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/inventory/{id}/update-quantity', [InventoryController::class, 'updateQuantity'])->name('inventory.updateQuantity');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');
        Route::get('/orders/new', [OrderController::class, 'create'])->name('orders.new');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::get('/inventory/dashboard', [App\Http\Controllers\Supplier\InventoryController::class, 'dashboard'])->name('inventory.dashboard');
        Route::get('/stockin', [App\Http\Controllers\Supplier\StockInController::class, 'index'])->name('stockin.index');
        Route::get('/stockin/create', [App\Http\Controllers\Supplier\StockInController::class, 'create'])->name('stockin.create');
        Route::post('/stockin', [App\Http\Controllers\Supplier\StockInController::class, 'store'])->name('stockin.store');
        Route::post('/stockin/test', function () {
            dd('Form submitted!');
        })->name('stockin.test');
        Route::get('/stockout', [App\Http\Controllers\Supplier\StockOutController::class, 'index'])->name('stockout.index');
        Route::get('/stockout/create', [App\Http\Controllers\Supplier\StockOutController::class, 'create'])->name('stockout.create');
        Route::post('/stockout', [App\Http\Controllers\Supplier\StockOutController::class, 'store'])->name('stockout.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bakery Manager Routes
    Route::middleware(['auth', 'role:bakery_manager'])->prefix('bakery')->name('bakery.')->group(function () {
        // Resource routes for bakery management
        Route::resource('batches', \App\Http\Controllers\ProductionBatchController::class);
        Route::resource('ingredients', \App\Http\Controllers\IngredientController::class);
        Route::resource('machines', \App\Http\Controllers\MachineController::class);
        Route::resource('maintenance-tasks', \App\Http\Controllers\MaintenanceTaskController::class);
        Route::resource('shifts', \App\Http\Controllers\ShiftController::class);
        // Production Routes
        Route::get('/production', function () {
            return view('bakery.production');
        })->name('production');

        // API endpoint for production batches (AJAX/live updates)
        Route::get('/api/production-batches', [\App\Http\Controllers\ProductionBatchController::class, 'apiIndex'])->name('production-batches.api');

        Route::get('/production/start', [ProductionController::class, 'start'])->name('production.start');
        Route::post('/production/start', [ProductionController::class, 'store'])->name('production.store');

        // Schedule Routes
        Route::get('/schedule', [\App\Http\Controllers\Bakery\ScheduleController::class, 'index'])->name('schedule');

        Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
        Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');

        // Maintenance Routes
        Route::get('/maintenance', [\App\Http\Controllers\Bakery\MaintenanceController::class, 'index'])->name('maintenance');

        Route::get('/maintenance/schedule', [MaintenanceController::class, 'schedule'])->name('maintenance.schedule');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

        // API endpoint for active staff (live updates)
        Route::get('/api/active-staff', [\App\Http\Controllers\ShiftController::class, 'apiActiveStaff'])->name('active-staff.api');

        // Live dashboard API endpoints
        Route::get('/api/production-live', [\App\Http\Controllers\DashboardController::class, 'productionLive'])->name('bakery.production-live');
        Route::get('/api/workforce-live', [\App\Http\Controllers\DashboardController::class, 'workforceLive'])->name('bakery.workforce-live');
        Route::get('/api/machines-live', [\App\Http\Controllers\DashboardController::class, 'machinesLive'])->name('bakery.machines-live');
        Route::get('/api/ingredients-live', [\App\Http\Controllers\DashboardController::class, 'ingredientsLive'])->name('bakery.ingredients-live');
        Route::get('/api/notifications-live', [\App\Http\Controllers\DashboardController::class, 'notificationsLive'])->name('bakery.notifications-live');
        Route::get('/api/chat-live', [\App\Http\Controllers\DashboardController::class, 'chatLive'])->name('bakery.chat-live');

        // Workforce Management
        Route::post('/workforce/assign-task', [\App\Http\Controllers\WorkforceController::class, 'assignTask'])->name('workforce.assign-task');
        Route::post('/workforce/update-task/{task}', [\App\Http\Controllers\WorkforceController::class, 'updateTaskStatus'])->name('workforce.update-task');
        Route::get('/workforce/tasks', [\App\Http\Controllers\WorkforceController::class, 'getTasks'])->name('workforce.tasks');
        Route::post('/workforce/auto-reassign', [\App\Http\Controllers\WorkforceController::class, 'autoReassignAbsentees'])->name('workforce.auto-reassign');
    });

    // Retail Manager Routes
    Route::middleware(['auth', 'role:retail_manager'])->prefix('retail')->name('retail.')->group(function () {
        Route::resource('orders', App\Http\Controllers\Retail\OrderController::class);
        Route::post('/orders/{order}/status', [App\Http\Controllers\Retail\OrderController::class, 'changeStatus'])->name('orders.changeStatus');

        Route::get('/inventory', [App\Http\Controllers\Retail\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/check', [App\Http\Controllers\Retail\InventoryController::class, 'check'])->name('inventory.check');
        Route::post('/inventory/update', [App\Http\Controllers\Retail\InventoryController::class, 'update'])->name('inventory.update');

        Route::get('/forecast', [App\Http\Controllers\Retail\ForecastController::class, 'index'])->name('forecast.index');
        Route::get('/forecast/generate', [App\Http\Controllers\Retail\ForecastController::class, 'generate'])->name('forecast.generate');

        Route::get('/chat', [App\Http\Controllers\Retail\ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [App\Http\Controllers\Retail\ChatController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages', [App\Http\Controllers\Retail\ChatController::class, 'getMessages'])->name('chat.get-messages');

        Route::post('/payments/{payment}/confirm', [App\Http\Controllers\Retail\PaymentController::class, 'confirm'])->name('payments.confirm');
        Route::post('/payments/{payment}/refund', [App\Http\Controllers\Retail\PaymentController::class, 'refund'])->name('payments.refund');

        Route::get('/dashboard', [App\Http\Controllers\Retail\DashboardController::class, 'index'])->name('dashboard.retail');

        Route::post('/orders/{order}/return', [\App\Http\Controllers\OrderReturnController::class, 'store'])->name('orders.return');
        Route::get('/returns', [\App\Http\Controllers\OrderReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{id}', [\App\Http\Controllers\OrderReturnController::class, 'show'])->name('returns.show');

        Route::post('/support', [\App\Http\Controllers\SupportRequestController::class, 'store'])->name('support.store');
        Route::get('/support', [\App\Http\Controllers\SupportRequestController::class, 'index'])->name('support.index');
        Route::get('/support/{id}', [\App\Http\Controllers\SupportRequestController::class, 'show'])->name('support.show');
    });

    // Distributor Routes
    Route::middleware(['auth', 'role:distributor'])->prefix('distributor')->name('distributor.')->group(function () {
        Route::get('/routes', [\App\Http\Controllers\Distributor\RouteController::class, 'index'])->name('routes');
        Route::get('/routes/create', [\App\Http\Controllers\Distributor\RouteController::class, 'create'])->name('routes.create');
        Route::post('/routes', [\App\Http\Controllers\Distributor\RouteController::class, 'store'])->name('routes.store');
        Route::get('/vehicles', [\App\Http\Controllers\Distributor\VehicleController::class, 'index'])->name('vehicles');
        Route::get('/vehicles/assign', [\App\Http\Controllers\Distributor\VehicleController::class, 'assign'])->name('vehicles.assign');
        Route::post('/vehicles/assign', [\App\Http\Controllers\Distributor\VehicleController::class, 'storeAssignment'])->name('vehicles.storeAssignment');
        Route::get('/deliveries', [\App\Http\Controllers\Distributor\DeliveryController::class, 'index'])->name('deliveries');
        Route::get('/deliveries/confirm', [\App\Http\Controllers\Distributor\DeliveryController::class, 'confirm'])->name('deliveries.confirm');
        Route::post('/deliveries/confirm', [\App\Http\Controllers\Distributor\DeliveryController::class, 'storeConfirmation'])->name('deliveries.storeConfirmation');
    });

    // Customer Chat Routes
    Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/chat', [App\Http\Controllers\Customer\ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [App\Http\Controllers\Customer\ChatController::class, 'send'])->name('chat.send');
    });
});

// Vendor Registration Routes
Route::get('/vendor/register', [VendorController::class, 'register'])->name('vendor.register');
Route::post('/vendor/register', [VendorController::class, 'store'])->name('vendor.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Supplier Chat Routes
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/chat', [App\Http\Controllers\Supplier\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [App\Http\Controllers\Supplier\ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [App\Http\Controllers\Supplier\ChatController::class, 'getMessages'])->name('chat.get-messages');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/chat-dashboard', [App\Http\Controllers\ChatDashboardController::class, 'index'])->name('chat.dashboard');
});

// Add these routes for customer segments import
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/customer-segments/import', function() {
        return view('customer_segments.import');
    })->name('customer-segments.import.form');
    // If you have a controller method for import, you can use it instead:
    // Route::get('/customer-segments/import', [CustomerSegmentController::class, 'importForm'])->name('customer-segments.import.form');
    Route::post('/customer-segments/import', [App\Http\Controllers\CustomerSegmentImportController::class, 'import'])->name('customer-segments.import');
});

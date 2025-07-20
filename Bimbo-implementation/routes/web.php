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
use App\Http\Controllers\ReportDownloadController;
use App\Models\Order;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('vendors', VendorController::class);
        Route::resource('users', UserController::class);
        Route::get('/orders/analytics', [\App\Http\Controllers\Admin\OrderController::class, 'analytics'])->name('orders.analytics');
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/inventory', [AnalyticsController::class, 'adminInventoryAnalytics'])->name('analytics.inventory');
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
        // Add browser-based segmentation pipeline routes
        Route::post('/customer-segments/upload-dataset', [\App\Http\Controllers\CustomerSegmentController::class, 'uploadDataset'])->name('customer-segments.upload-dataset');
        Route::post('/customer-segments/run-segmentation', [\App\Http\Controllers\CustomerSegmentController::class, 'runSegmentation'])->name('customer-segments.run-segmentation');
        Route::post('/customer-segments/import-segments', [\App\Http\Controllers\CustomerSegmentController::class, 'importSegments'])->name('customer-segments.import-segments');
        // Order analytics
        Route::get('/api/orders', [\App\Http\Controllers\Admin\OrderController::class, 'apiOrders'])->name('orders.api');
        Route::get('/api/orders/stats', [\App\Http\Controllers\Admin\OrderController::class, 'apiStats'])->name('orders.stats');
        Route::get('/analytics/sales-predictions', [AnalyticsController::class, 'salesPredictions'])->name('analytics.sales_predictions');
    });

    // Combine all supplier routes into a single group
    Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        // Inventory routes
        Route::get('/inventory', [App\Http\Controllers\Supplier\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [App\Http\Controllers\Supplier\InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [App\Http\Controllers\Supplier\InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{id}/edit', [App\Http\Controllers\Supplier\InventoryController::class, 'edit'])->name('inventory.edit');
        Route::put('/inventory/{id}', [App\Http\Controllers\Supplier\InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/inventory/{id}', [App\Http\Controllers\Supplier\InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/inventory/{id}/update-quantity', [App\Http\Controllers\Supplier\InventoryController::class, 'updateQuantity'])->name('inventory.updateQuantity');
        Route::get('/inventory/dashboard', [App\Http\Controllers\Supplier\InventoryController::class, 'dashboard'])->name('inventory.dashboard');
        Route::get('/inventory/{id}', [App\Http\Controllers\Supplier\InventoryController::class, 'show'])->name('inventory.show');

        // Supplier dashboard route
        Route::get('/dashboard', function () {
            return view('supplier.index');
        })->name('dashboard');

        // Orders routes
        Route::get('/orders', [App\Http\Controllers\Supplier\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/new', [App\Http\Controllers\Supplier\OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [App\Http\Controllers\Supplier\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [App\Http\Controllers\Supplier\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [App\Http\Controllers\Supplier\OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Stockin routes
        Route::get('/stockin', [App\Http\Controllers\Supplier\StockInController::class, 'index'])->name('stockin.index');
        Route::get('/stockin/create', [App\Http\Controllers\Supplier\StockInController::class, 'create'])->name('stockin.create');
        Route::post('/stockin', [App\Http\Controllers\Supplier\StockInController::class, 'store'])->name('stockin.store');
        Route::post('/stockin/test', function () {
            dd('Form submitted!');
        })->name('stockin.test');

        // Stockout routes
        Route::get('/stockout', [App\Http\Controllers\Supplier\StockOutController::class, 'index'])->name('stockout.index');
        Route::get('/stockout/create', [App\Http\Controllers\Supplier\StockOutController::class, 'create'])->name('stockout.create');
        Route::post('/stockout', [App\Http\Controllers\Supplier\StockOutController::class, 'store'])->name('stockout.store');

        // Chat routes
        Route::get('/chat', [App\Http\Controllers\Supplier\ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [App\Http\Controllers\Supplier\ChatController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages', [App\Http\Controllers\Supplier\ChatController::class, 'getMessages'])->name('chat.get-messages');
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
        Route::post('/workforce/assign-staff', [\App\Http\Controllers\WorkforceController::class, 'assignStaff'])->name('workforce.assign-staff');
        Route::post('/workforce/assign-staff-bulk', [\App\Http\Controllers\WorkforceController::class, 'assignStaffBulk']);
        // Assign shift to batch
        Route::post('/shifts/assign', [\App\Http\Controllers\ShiftController::class, 'assignToBatch'])->name('shifts.assignToBatch');
        Route::post('/shifts/assign-new', [\App\Http\Controllers\ShiftController::class, 'assignNewToBatch'])->name('shifts.assignNewToBatch');
        // Assign shift to batch (AJAX)
        Route::post('/batches/{batch}/assign-shift', [\App\Http\Controllers\ProductionBatchController::class, 'assignShift'])->name('batches.assignShift');
        Route::get('/order-processing', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'index'])->name('order-processing');
        Route::post('/order-processing/supplier-order', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'placeSupplierOrder'])->name('order-processing.supplier-order');
        Route::get('/order-processing/retailer-orders', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'listRetailerOrders'])->name('order-processing.retailer-orders');
        Route::post('/order-processing/retailer-orders/{id}/receive', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'receiveRetailerOrder'])->name('order-processing.retailer-orders.receive');
        Route::get('/workforce/shifts', [\App\Http\Controllers\WorkforceController::class, 'shifts'])->name('workforce.shifts');
        Route::post('/workforce/shifts', [\App\Http\Controllers\WorkforceController::class, 'storeShift'])->name('workforce.shifts.store');
        Route::get('/workforce/assignment', [\App\Http\Controllers\WorkforceController::class, 'assignment'])->name('workforce.assignment');
        Route::get('/workforce/availability', [\App\Http\Controllers\WorkforceController::class, 'availability'])->name('workforce.availability');

        // Order Processing Route
        // Route::get('/order-processing', function () {
        //     $products = \App\Models\Product::all();
        //     $suppliers = \App\Models\User::where('role', 'supplier')->get();
        //     $retailerOrders = \App\Models\RetailerOrder::all();
        //     return view('bakery.order-processing', compact('products', 'suppliers', 'retailerOrders'));
        // })->name('order-processing');

        // Order Processing AJAX/Form Endpoints
        Route::post('/order-processing/supplier-order', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'placeSupplierOrder'])->name('order-processing.supplier-order');
        // Correct route for AJAX retailer orders
        Route::get('/order-processing/retailer-orders', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'listRetailerOrders'])->name('order-processing.retailer-orders');
        Route::post('/order-processing/retailer-orders/{id}/status', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'updateRetailerOrderStatus'])->name('order-processing.retailer-orders.update-status');

        // Inventory routes
        Route::get('/inventory', [\App\Http\Controllers\Bakery\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [\App\Http\Controllers\Bakery\InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [\App\Http\Controllers\Bakery\InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{inventory}', [\App\Http\Controllers\Bakery\InventoryController::class, 'show'])->name('inventory.show');
        Route::get('/inventory/{inventory}/edit', [\App\Http\Controllers\Bakery\InventoryController::class, 'edit'])->name('inventory.edit');
        Route::delete('/inventory/{id}', [\App\Http\Controllers\Bakery\InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/inventory/{inventory}/update-stock', [\App\Http\Controllers\Bakery\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
        Route::get('/api/inventory/{id}/chart-data', [\App\Http\Controllers\Bakery\InventoryController::class, 'chartData'])->name('inventory.chart-data');
        Route::get('/api/inventory/{id}/live', [\App\Http\Controllers\Bakery\InventoryController::class, 'liveData'])->name('inventory.live-data');
        Route::get('/api/inventory/{id}/recent-orders', [\App\Http\Controllers\Bakery\InventoryController::class, 'recentOrders'])->name('inventory.recent-orders');

        Route::get('/dashboard', [App\Http\Controllers\Bakery\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/production-stats-live', [\App\Http\Controllers\DashboardController::class, 'productionStatsLive'])->name('bakery.production-stats-live');
    });

    // Retail Manager Routes
    Route::middleware(['auth', 'role:retail_manager'])->prefix('retail')->name('retail.')->group(function () {
        // Inventory routes
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [App\Http\Controllers\Retail\InventoryController::class, 'index'])->name('index');
            Route::get('/check', [App\Http\Controllers\Retail\InventoryController::class, 'check'])->name('check');
            Route::get('/create', [App\Http\Controllers\Retail\InventoryController::class, 'create'])->name('create');
            Route::post('/update', [App\Http\Controllers\Retail\InventoryController::class, 'update'])->name('update');
            Route::get('/{inventory}', [App\Http\Controllers\Retail\InventoryController::class, 'show'])->name('show');
        });

        // Orders routes
        Route::resource('orders', App\Http\Controllers\Retail\OrderController::class);
        Route::post('/orders/{order}/status', [App\Http\Controllers\Retail\OrderController::class, 'changeStatus'])->name('orders.changeStatus');
        Route::post('/orders/{order}/record-payment', [App\Http\Controllers\Retail\OrderController::class, 'recordPayment'])->name('orders.recordPayment');

        Route::get('/inventory', [App\Http\Controllers\Retail\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [App\Http\Controllers\Retail\InventoryController::class, 'create'])->name('inventory.create');
        Route::get('/inventory/check', [App\Http\Controllers\Retail\InventoryController::class, 'check'])->name('inventory.check');
        Route::post('/inventory/update', [App\Http\Controllers\Retail\InventoryController::class, 'update'])->name('inventory.update');

        // Forecast routes
        Route::get('/forecast', [App\Http\Controllers\Retail\ForecastController::class, 'index'])->name('forecast.index');
        Route::get('/forecast/generate', [App\Http\Controllers\Retail\ForecastController::class, 'generate'])->name('forecast.generate');

        // Chat routes
        Route::get('/chat', [App\Http\Controllers\Retail\ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [App\Http\Controllers\Retail\ChatController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages', [App\Http\Controllers\Retail\ChatController::class, 'getMessages'])->name('chat.get-messages');

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Retail\DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [App\Http\Controllers\Retail\ProductController::class, 'index'])->name('products.index');

        // Cart routes
        Route::get('/cart', [App\Http\Controllers\Retail\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [App\Http\Controllers\Retail\CartController::class, 'store'])->name('cart.add');
        Route::put('/cart/{id}', [App\Http\Controllers\Retail\CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [App\Http\Controllers\Retail\CartController::class, 'destroy'])->name('cart.destroy');
        Route::post('/cart/checkout', [App\Http\Controllers\Retail\CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/cart/place-order', [App\Http\Controllers\Retail\CartController::class, 'placeOrder'])->name('cart.place-order');
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
        // Order management for distributors
        Route::get('/orders', [\App\Http\Controllers\Distributor\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Distributor\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/delivery-status', [\App\Http\Controllers\Distributor\OrderController::class, 'updateDeliveryStatus'])->name('orders.updateDeliveryStatus');
        Route::get('/api/orders/route', [\App\Http\Controllers\Distributor\OrderController::class, 'routeOrders'])->name('orders.route');
        Route::get('/api/orders/stats', [\App\Http\Controllers\Distributor\OrderController::class, 'deliveryStats'])->name('orders.stats');
        Route::get('/api/orders', [\App\Http\Controllers\Distributor\OrderController::class, 'apiOrders'])->name('orders.api');
    });

    // Customer Routes
    Route::middleware(['web', 'auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/chat', [App\Http\Controllers\Customer\ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/send', [App\Http\Controllers\Customer\ChatController::class, 'send'])->name('chat.send');
        // Customer order routes
        Route::get('/order/create', [App\Http\Controllers\Customer\OrderController::class, 'create'])->name('order.create');
        Route::post('/order/store', [App\Http\Controllers\Customer\OrderController::class, 'store'])->name('order.store');
        Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/cart', [\App\Http\Controllers\Customer\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [\App\Http\Controllers\Customer\CartController::class, 'store'])->name('cart.add');
        Route::put('/cart/{id}', [\App\Http\Controllers\Customer\CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [\App\Http\Controllers\Customer\CartController::class, 'destroy'])->name('cart.destroy');
        Route::get('/cart/checkout', [\App\Http\Controllers\Customer\CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/cart/place-order', [\App\Http\Controllers\Customer\CartController::class, 'placeOrder'])->name('cart.place-order');
        Route::get('/products', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('products');
    });

    // Workforce Overview Route
    Route::get('/workforce/overview', [WorkforceController::class, 'overview'])->name('workforce.overview');

    // Remove or comment out the following conflicting route:
    // Route::get('/supplier/orders', [\App\Http\Controllers\SupplierOrderController::class, 'index'])
    //     ->name('supplier.orders');
});

// Vendor Registration Routes
Route::get('/vendor/register', [VendorController::class, 'register'])->name('vendor.register');
Route::post('/vendor/register', [VendorController::class, 'store'])->name('vendor.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Supplier Chat Routes
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/chat', [App\Http\Controllers\Supplier\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [App\Http\Controllers\Supplier\ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages', [App\Http\Controllers\Supplier\ChatController::class, 'getMessages'])->name('chat.get-messages');
});

// Supplier Raw Material Ordering
Route::middleware(['auth', 'role:supplier,bakery_manager,retail_manager'])->prefix('supplier/raw-materials')->name('supplier.raw-materials.')->group(function () {
    Route::get('catalog', [\App\Http\Controllers\Supplier\RawMaterialOrderController::class, 'catalog'])->name('catalog');
    Route::post('add-to-cart', [\App\Http\Controllers\Supplier\RawMaterialOrderController::class, 'addToCart'])->name('addToCart');
    Route::get('cart', [\App\Http\Controllers\Supplier\RawMaterialOrderController::class, 'cart'])->name('cart');
    Route::post('remove-from-cart/{index}', [\App\Http\Controllers\Supplier\RawMaterialOrderController::class, 'removeFromCart'])->name('removeFromCart');
});
// Only bakery_manager can access checkout
Route::middleware(['auth', 'role:bakery_manager'])->prefix('supplier/raw-materials')->name('supplier.raw-materials.')->group(function () {
    Route::post('checkout', [\App\Http\Controllers\Supplier\RawMaterialOrderController::class, 'checkout'])->name('checkout');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/chat-dashboard', [App\Http\Controllers\ChatDashboardController::class, 'index'])->name('chat.dashboard');
});

// Add these routes for customer segments import
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/customer-segments/import', function () {
        return view('customer_segments.import');
    })->name('customer-segments.import.form');
    // If you have a controller method for import, you can use it instead:
    // Route::get('/customer-segments/import', [CustomerSegmentController::class, 'importForm'])->name('customer-segments.import.form');
    Route::post('/customer-segments/import', [App\Http\Controllers\CustomerSegmentImportController::class, 'import'])->name('customer-segments.import');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // ... existing routes ...
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    //Route::get('/reports/{type}/{filename}', [\App\Http\Controllers\ReportController::class, 'download'])->name('reports.download');
    Route::post('/reports/generate', [\App\Http\Controllers\ReportDownloadController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/view/{filename}', [\App\Http\Controllers\ReportDownloadController::class, 'view'])
        ->where('filename', '.*')
        ->name('reports.view');
    Route::get('/reports/weekly/view/{filename}', [\App\Http\Controllers\ReportDownloadController::class, 'weeklyView'])
        ->where('filename', '.*')
        ->name('reports.weekly.view');
});

// Add this route for report downloads
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/reports/downloads', [\App\Http\Controllers\ReportDownloadController::class, 'index'])->name('reports.downloads');
});

// Make bakery.workforce.auto-assign available globally for dashboard
Route::post('/workforce/auto-assign', [\App\Http\Controllers\WorkforceController::class, 'autoAssignStaff'])
    ->middleware(['auth', 'verified', 'role:bakery_manager'])
    ->name('bakery.workforce.auto-assign');

// Add this route for bakery stats live
Route::get('/bakery/stats-live', [DashboardController::class, 'statsLive'])->name('bakery.bakery.stats-live');

// Add this route for bakery inventory index
Route::get('/bakery/inventory', [\App\Http\Controllers\Bakery\InventoryController::class, 'index'])->name('bakery.inventory.index');
// Add this route for bakery inventory create
Route::get('/bakery/inventory/create', [\App\Http\Controllers\Bakery\InventoryController::class, 'create'])->name('bakery.inventory.create');
// Add this route for bakery inventory show
Route::get('/bakery/inventory/{inventory}', [\App\Http\Controllers\Bakery\InventoryController::class, 'show'])->name('bakery.inventory.show');
// Add this route for bakery inventory edit
Route::get('/bakery/inventory/{inventory}/edit', [\App\Http\Controllers\Bakery\InventoryController::class, 'edit'])->name('bakery.inventory.edit');
// Add this route for bakery inventory destroy
Route::delete('/bakery/inventory/{id}', [\App\Http\Controllers\Bakery\InventoryController::class, 'destroy'])->name('bakery.inventory.destroy');

// Add this route for bakery inventory update stock
Route::post('/bakery/inventory/{inventory}/update-stock', [\App\Http\Controllers\Bakery\InventoryController::class, 'updateStock'])->name('bakery.inventory.update-stock');

// Add this route for bakery inventory chart data
Route::get('/bakery/api/inventory/{id}/chart-data', [\App\Http\Controllers\Bakery\InventoryController::class, 'chartData'])->name('bakery.inventory.chart-data');

// Add this route for bakery inventory live data
Route::get('/bakery/api/inventory/{id}/live', [\App\Http\Controllers\Bakery\InventoryController::class, 'liveData'])->name('bakery.inventory.live-data');

// Add this route for bakery inventory recent orders
Route::get('/bakery/api/inventory/{id}/recent-orders', [\App\Http\Controllers\Bakery\InventoryController::class, 'recentOrders'])->name('bakery.inventory.recent-orders');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/analytics/inventory', [\App\Http\Controllers\Admin\AnalyticsController::class, 'adminInventoryAnalytics'])->name('admin.analytics.inventory');
    Route::get('analytics/graphs', [\App\Http\Controllers\Admin\AnalyticsController::class, 'graphs'])->name('analytics.graphs');
});

Route::get('/reports/download/{type}/{filename}', [ReportDownloadController::class, 'download'])
    ->name('reports.download')
    ->where('filename', '.*');

Route::get('/reports/view/{filename}', [ReportDownloadController::class, 'view'])
    ->name('reports.view')
    ->where('filename', '.*');

// Add this route for supplier orders AJAX endpoint
Route::middleware(['auth', 'role:bakery_manager'])->group(function () {
    Route::get('/order-processing/supplier-orders', [\App\Http\Controllers\Bakery\OrderProcessingController::class, 'listSupplierOrders']);
});

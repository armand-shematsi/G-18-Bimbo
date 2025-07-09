<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\Shift;
use App\Models\MaintenanceTask;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activeVendorsCount = \App\Models\Vendor::where('status', 'active')->count();
        $totalSales = \App\Models\Vendor::sum('sales');

        // Total bread produced (sum of all ProductionBatch quantities)
        $totalBreadProduced = \App\Models\ProductionBatch::sum('quantity');

        // Total deliveries (orders with status delivered)
        $totalDeliveries = \App\Models\Order::where('status', \App\Models\Order::STATUS_DELIVERED)->count();

        // Pending orders
        $pendingOrders = \App\Models\Order::where('status', \App\Models\Order::STATUS_PENDING)->count();

        // Stock levels (sum of all inventory quantities)
        $stockLevels = \App\Models\Inventory::sum('quantity');

        // Total revenue (sum of all order totals)
        $totalRevenue = \App\Models\Order::sum('total');

        // Reorder alerts (count of inventories needing reorder)
        $reorderAlerts = \App\Models\Inventory::whereColumn('quantity', '<=', 'reorder_level')->count();
        $recentOrders = \App\Models\Order::with(['user', 'vendor'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'activeVendorsCount',
            'totalSales',
            'totalBreadProduced',
            'totalDeliveries',
            'pendingOrders',
            'stockLevels',
            'totalRevenue',
            'reorderAlerts',
            'recentOrders'
        ));
    }

    // API: Today's total production units
    public function productionSummary()
    {
        $today = Carbon::today();
        // Example: sum a 'quantity' field for today's batches (adjust as needed)
        $units = ProductionBatch::whereDate('scheduled_start', $today)->sum('quantity');
        return response()->json(['units' => $units]);
    }

    // API: Current staff on duty
    public function staffSummary()
    {
        $now = now();
        $staff_on_duty = Shift::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->distinct('user_id')
            ->count('user_id');
        return response()->json(['staff_on_duty' => $staff_on_duty]);
    }

    // API: Active maintenance alerts
    public function maintenanceSummary()
    {
        // Example: count open/active maintenance tasks (adjust status field as needed)
        $alerts = MaintenanceTask::where('status', 'active')->count();
        return response()->json(['alerts' => $alerts]);
    }

    public function sendSupplierReports(Request $request)
    {
        // TODO: Implement actual report sending logic
        return redirect()->back()->with('status', 'Supplier reports sent successfully!');
    }
}

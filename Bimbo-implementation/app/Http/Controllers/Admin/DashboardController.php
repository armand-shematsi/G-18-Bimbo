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
        return view('admin.dashboard', compact('activeVendorsCount', 'totalSales'));
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
}

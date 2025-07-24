<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // You can add bakery-specific dashboard data here
        $products = \App\Models\Product::all();
        return view('dashboard.bakery-manager', compact('products'));
    }

    public function productionOverview()
    {
        $today = Carbon::today();

        // Get actual counts based on current status, not just dates
        $todayBatches = ProductionBatch::whereDate('scheduled_start', $today)->count();
        $completedBatches = ProductionBatch::where('status', 'completed')->count();
        $inProgressBatches = ProductionBatch::where('status', 'active')->count();

        return response()->json([
            'todayBatches' => $todayBatches,
            'completedBatches' => $completedBatches,
            'inProgressBatches' => $inProgressBatches,
        ]);
    }

    public function recentActivity()
    {
        // First try to get from ActivityLog
        $activities = ActivityLog::latest()->limit(10)->get();

        if ($activities->isEmpty()) {
            // Fallback: Get recent batch changes
            $recentBatches = ProductionBatch::latest()->limit(10)->get();
            $activities = $recentBatches->map(function ($batch) {
                $status = $batch->status;
                $map = [
                    'planned' => ['color' => 'gray', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>', 'status' => 'Planned'],
                    'active' => ['color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>', 'status' => 'Active'],
                    'completed' => ['color' => 'green', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>', 'status' => 'Completed'],
                    'cancelled' => ['color' => 'red', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414L12 9.172 7.05 4.222l-1.414 1.414L10.828 12l-5.192 5.192 1.414 1.414L12 14.828l4.95 4.95 1.414-1.414L13.172 12z"/>', 'status' => 'Cancelled'],
                ];
                $meta = $map[$status] ?? ['color' => 'gray', 'icon' => '', 'status' => ucfirst($status)];

                return [
                    'title' => $batch->name . ' Batch - ' . ucfirst($status),
                    'time' => $batch->updated_at->diffForHumans(),
                    'color' => $meta['color'],
                    'icon' => $meta['icon'],
                    'status' => $meta['status'],
                ];
            });
        } else {
            // Use ActivityLog data
            $activities = $activities->map(function ($log) {
                $map = [
                    'started' => ['color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>', 'status' => 'Started'],
                    'completed' => ['color' => 'green', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>', 'status' => 'Completed'],
                    'alert' => ['color' => 'orange', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>', 'status' => 'Alert'],
                ];
                $type = $log->action;
                $meta = $map[$type] ?? ['color' => 'gray', 'icon' => '', 'status' => ucfirst($type)];
                return [
                    'title' => $log->description,
                    'time' => $log->created_at->diffForHumans(),
                    'color' => $meta['color'],
                    'icon' => $meta['icon'],
                    'status' => $meta['status'],
                ];
            });
        }

        return response()->json(['activities' => $activities]);
    }
}

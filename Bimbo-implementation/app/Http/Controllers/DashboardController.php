<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                $activeVendorsCount = \App\Models\Vendor::where('status', 'active')->count();
                return view('dashboard.admin', compact('activeVendorsCount'));
            case 'supplier':
                return view('dashboard.supplier');
            case 'bakery_manager':
                return view('dashboard.bakery-manager');
            case 'distributor':
                return view('dashboard.distributor');
            case 'retail_manager':
                $supplierInventory = \App\Models\Inventory::whereHas('user', function ($query) {
                    $query->where('role', 'supplier');
                })->get();

                $lowStockItems = $supplierInventory->filter(function ($item) {
                    return $item->needsReorder();
                });

                return view('dashboard.retail-manager', compact('supplierInventory', 'lowStockItems'));
            case 'customer':
                return view('dashboard.customer');
            default:
                // Log out the user and redirect to login with error message
                \Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Unauthorized role. Please contact support.']);
        }
    }

    /**
     * API: Get workforce distribution by supply center, with optional filters.
     */
    public function workforceDistribution(Request $request)
    {
        $query = \App\Models\SupplyCenter::with(['users' => function ($q) use ($request) {
            if ($request->has('role')) {
                $q->where('role', $request->input('role'));
            }
            if ($request->has('name')) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            }
        }]);
        if ($request->has('center')) {
            $query->where('id', $request->input('center'));
        }
        $centers = $query->get();
        return response()->json($centers);
    }

    /**
     * API: Live production data (output, target, batches)
     */
    public function productionLive()
    {
        // Get all batches for today
        $today = now()->toDateString();
        $batches = \App\Models\ProductionBatch::with(['shifts.user'])
            ->whereDate('scheduled_start', $today)
            ->orderBy('scheduled_start', 'desc')
            ->get();
        $batchData = $batches->map(function ($batch) {
            return [
                'name' => $batch->name,
                'status' => $batch->status,
                'scheduled_start' => $batch->scheduled_start,
                'actual_start' => $batch->actual_start,
                'actual_end' => $batch->actual_end,
                'notes' => $batch->notes,
                'assigned_staff' => $batch->shifts->map(function($shift) {
                    return $shift->user ? $shift->user->name : 'Unassigned';
                })->join(', '),
            ];
        });
        // Trends: batches completed per day for last 7 days
        $trends = [];
        $trendLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trendLabels[] = $date;
            $count = \App\Models\ProductionBatch::whereDate('actual_end', $date)->where('status', 'Completed')->count();
            $trends[] = $count;
        }
        $output = $batches->where('status', 'Completed')->count();
        $active = $batches->whereIn('status', ['Active', 'active'])->count();
        $downtime = 0; // Placeholder, implement logic if available
        return response()->json([
            'output' => $output,
            'active' => $active,
            'batches_today' => $batches->count(),
            'batches' => $batchData,
            'trends' => $trends,
            'trend_labels' => $trendLabels,
            'downtime' => $downtime,
        ]);
    }

    /**
     * API: Live workforce data (staff on duty, assignments)
     */
    public function workforceLive()
    {
        // Staff on duty: users marked present today
        $today = now()->toDateString();
        $staffOnDuty = \App\Models\User::whereHas('attendances', function ($q) use ($today) {
            $q->where('date', $today)->where('status', 'present');
        })->get(['id', 'name', 'role']);

        // Assignments: active shifts for today with user info
        $assignments = \App\Models\Shift::whereDate('start_time', $today)
            ->whereNotNull('user_id')
            ->with(['user'])
            ->get()
            ->map(function ($shift) {
                return [
                    'staff' => $shift->user ? $shift->user->name : null,
                    'role' => $shift->user ? $shift->user->role : null,
                    'shift_id' => $shift->id,
                    'start_time' => $shift->start_time,
                    'end_time' => $shift->end_time,
                    'status' => $shift->status ?? null,
                ];
            });

        return response()->json([
            'staff' => $staffOnDuty,
            'assignments' => $assignments,
        ]);
    }

    /**
     * API: Live machine data (status, alerts)
     */
    public function machinesLive()
    {
        return response()->json([
            'machines' => [
                ['name' => 'Oven 1', 'status' => 'Running'],
                ['name' => 'Oven 2', 'status' => 'Maintenance'],
            ],
            'alerts' => [
                'Oven 2 scheduled for maintenance at 15:00.'
            ],
        ]);
    }

    /**
     * API: Live ingredient data (stock, alerts)
     */
    public function ingredientsLive()
    {
        return response()->json([
            'ingredients' => [
                ['name' => 'Flour', 'stock' => 20, 'alert' => 'Low stock'],
                ['name' => 'Yeast', 'stock' => 100, 'alert' => null],
            ],
        ]);
    }

    /**
     * API: Live notifications
     */
    public function notificationsLive()
    {
        return response()->json([
            'notifications' => [
                'Batch A completed successfully.',
                'Batch B scheduled to start at 13:00.',
                'John Smith assigned to Batch B.',
            ],
        ]);
    }

    /**
     * API: Live chat messages
     */
    public function chatLive()
    {
        return response()->json([
            'messages' => [
                ['user' => 'Jane', 'message' => 'Batch A is almost done!'],
                ['user' => 'John', 'message' => 'Oven 2 needs a check.'],
            ],
        ]);
    }
}

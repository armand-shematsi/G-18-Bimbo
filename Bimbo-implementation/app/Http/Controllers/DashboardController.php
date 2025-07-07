<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                // Fetch all new or assigned orders (pending or processing)
                $orders = \App\Models\Order::whereIn('status', ['pending', 'processing'])->orderBy('created_at', 'desc')->get();
                $staff = \App\Models\User::where('role', 'staff')->get();
                $supplyCenters = \App\Models\SupplyCenter::all();
                // Count active staff: those with a shift where now is between start_time and end_time
                $now = now();
                $activeStaffCount = \App\Models\Shift::whereNotNull('user_id')
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->distinct('user_id')
                    ->count('user_id');
                // Fetch production target from settings
                $productionTarget = optional(\App\Models\Setting::where('key', 'production_target')->first())->value;
                // Sum today's output from production batches
                $todaysOutput = \App\Models\ProductionBatch::whereDate('scheduled_start', now()->toDateString())->sum('quantity');
                return view('dashboard.bakery-manager', compact('orders', 'staff', 'supplyCenters', 'activeStaffCount', 'productionTarget', 'todaysOutput'));
            case 'distributor':
                return view('dashboard.distributor');
            case 'retail_manager':
                $supplierInventory = \App\Models\Inventory::whereHas('user', function ($query) {
                    $query->where('role', 'supplier');
                })->get();

                $lowStockItems = $supplierInventory->filter(function ($item) {
                    return $item->needsReorder();
                });

                // Compute order analytics for the last 7 days
                $orderDays = [];
                $orderCounts = [];
                $userId = $user->id;
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->toDateString();
                    $orderDays[] = $date;
                    $orderCounts[] = \App\Models\Order::where('user_id', $userId)
                        ->whereDate('created_at', $date)
                        ->count();
                }

                // Compute order status breakdown for this user
                $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
                $statusCounts = [];
                foreach ($statuses as $status) {
                    $statusCounts[$status] = \App\Models\Order::where('user_id', $userId)
                        ->where('status', $status)
                        ->count();
                }

                return view('dashboard.retail-manager', compact('supplierInventory', 'lowStockItems', 'orderDays', 'orderCounts', 'statusCounts'));
                                    case 'customer':
                // Get recent orders for the customer
                try {
                    $recentOrders = \App\Models\Order::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    // If Order model doesn't exist or table doesn't exist, use empty collection
                    $recentOrders = collect([]);
                }

                // Get recent messages for the customer (handle case where Message model might not exist)
                try {
                    $recentMessages = \App\Models\Message::where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    // If Message model doesn't exist or table doesn't exist, use empty collection
                    $recentMessages = collect([]);
                }

                return view('dashboard.customer', compact('recentOrders', 'recentMessages'));
            default:
                // Log out the user and redirect to login with error message
                Auth::logout();
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
        // Get batches for the last 7 days
        $batches = \App\Models\ProductionBatch::orderBy('scheduled_start', 'desc')->take(7)->get();
        $batchData = $batches->map(function ($batch) {
            return [
                'name' => $batch->name,
                'status' => $batch->status,
                'scheduled_start' => $batch->scheduled_start,
                'actual_start' => $batch->actual_start,
                'actual_end' => $batch->actual_end,
                'notes' => $batch->notes,
                'assigned_staff' => $batch->shifts->map(function ($shift) {
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
        $target = 7; // Example: target is 1 batch per day for a week
        return response()->json([
            'output' => $output,
            'target' => $target,
            'batches' => $batchData,
            'trends' => $trends,
            'trend_labels' => $trendLabels,
        ]);
    }

    /**
     * API: Live workforce data (staff on duty, assignments)
     */
    public function workforceLive()
    {
        return response()->json([
            'staff' => [
                ['name' => 'Jane Doe', 'role' => 'Baker'],
                ['name' => 'John Smith', 'role' => 'Operator'],
            ],
            'assignments' => [
                ['staff' => 'John Smith', 'batch' => 'Batch B'],
            ],
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

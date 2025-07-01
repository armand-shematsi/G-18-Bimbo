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
        // Example data, replace with real queries
        return response()->json([
            'output' => 1250,
            'target' => 1500,
            'batches' => [
                ['name' => 'Batch A', 'status' => 'Active', 'start' => '08:00', 'end' => '12:00'],
                ['name' => 'Batch B', 'status' => 'Planned', 'start' => '13:00', 'end' => '17:00'],
            ],
            'trends' => [1200, 1350, 1100, 1400, 1250, 1500, 1300],
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

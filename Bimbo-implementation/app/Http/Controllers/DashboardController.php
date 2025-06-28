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
                $supplierInventory = \App\Models\Inventory::whereHas('user', function($query) {
                    $query->where('role', 'supplier');
                })->get();
                
                $lowStockItems = $supplierInventory->filter(function($item) {
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
}

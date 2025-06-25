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
                return view('dashboard.retail-manager');
            case 'customer':
                return view('dashboard.customer');
            default:
                abort(403, 'Unauthorized role.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeVendorsCount = \App\Models\Vendor::where('status', 'active')->count();
        $totalSales = \App\Models\Vendor::sum('sales');
        return view('admin.dashboard', compact('activeVendorsCount', 'totalSales'));
    }
} 
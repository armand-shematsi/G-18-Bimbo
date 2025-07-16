<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // You can add bakery-specific dashboard data here
        return view('dashboard.bakery-manager');
    }
} 
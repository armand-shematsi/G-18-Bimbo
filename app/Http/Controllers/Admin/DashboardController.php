<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::all();
        dd($users); // Debugging statement
        $totalUsers = \App\Models\User::count();
        return view('admin.dashboard', compact('totalUsers'));
    }
} 
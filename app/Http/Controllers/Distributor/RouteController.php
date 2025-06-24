<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        return view('distributor.routes.index');
    }

    public function create()
    {
        return view('distributor.routes.create');
    }

    public function store(Request $request)
    {
        // Logic to store the new route
        return redirect()->route('distributor.routes')->with('success', 'Route created successfully.');
    }
} 
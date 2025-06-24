<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        return view('distributor.vehicles.index');
    }

    public function assign()
    {
        return view('distributor.vehicles.assign');
    }

    public function storeAssignment(Request $request)
    {
        // Logic to store the vehicle assignment
        return redirect()->route('distributor.vehicles')->with('success', 'Vehicle assigned successfully.');
    }
} 
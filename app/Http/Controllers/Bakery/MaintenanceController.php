<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function schedule()
    {
        return view('bakery.maintenance.schedule');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|in:routine,repair,emergency',
            'technician_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:255',
        ]);

        // TODO: Implement maintenance scheduling logic

        return redirect()->route('bakery.maintenance')
            ->with('success', 'Maintenance scheduled successfully.');
    }
} 
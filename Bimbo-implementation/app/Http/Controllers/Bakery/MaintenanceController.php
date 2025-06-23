<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use App\Models\MaintenanceTask;

class MaintenanceController extends Controller
{
    public function index()
    {
        $totalMachines = Machine::count();
        $requiresMaintenance = Machine::where('status', 'maintenance')->count();
        $underMaintenance = Machine::where('status', 'down')->count();
        $operational = Machine::where('status', 'operational')->count();

        $upcomingTasks = MaintenanceTask::with('machine')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_for')
            ->take(5)
            ->get();

        $recentTasks = MaintenanceTask::with('machine')
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->take(5)
            ->get();

        return view('bakery.maintenance', compact(
            'totalMachines',
            'requiresMaintenance',
            'underMaintenance',
            'operational',
            'upcomingTasks',
            'recentTasks'
        ));
    }

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
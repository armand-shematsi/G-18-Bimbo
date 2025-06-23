<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTask;
use App\Models\Machine;
use Illuminate\Http\Request;

class MaintenanceTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = MaintenanceTask::with('machine')->orderBy('scheduled_for', 'desc')->paginate(10);
        return view('bakery.maintenance_tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $machines = Machine::orderBy('name')->get();
        return view('bakery.maintenance_tasks.create', compact('machines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'scheduled_for' => 'required|date',
            'completed_at' => 'nullable|date',
            'description' => 'required|string',
            'status' => 'required|in:scheduled,completed,overdue',
        ]);
        MaintenanceTask::create($validated);
        return redirect()->route('bakery.maintenance-tasks.index')->with('success', 'Maintenance task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceTask $maintenanceTask)
    {
        $maintenanceTask->load('machine');
        return view('bakery.maintenance_tasks.show', compact('maintenanceTask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceTask $maintenanceTask)
    {
        $machines = Machine::orderBy('name')->get();
        return view('bakery.maintenance_tasks.edit', compact('maintenanceTask', 'machines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceTask $maintenanceTask)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'scheduled_for' => 'required|date',
            'completed_at' => 'nullable|date',
            'description' => 'required|string',
            'status' => 'required|in:scheduled,completed,overdue',
        ]);
        $maintenanceTask->update($validated);
        return redirect()->route('bakery.maintenance-tasks.index')->with('success', 'Maintenance task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceTask $maintenanceTask)
    {
        $maintenanceTask->delete();
        return redirect()->route('bakery.maintenance-tasks.index')->with('success', 'Maintenance task deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = Machine::orderBy('name')->paginate(10);
        return view('bakery.machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bakery.machines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|in:operational,maintenance,down',
            'last_maintenance_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        Machine::create($validated);
        return redirect()->route('bakery.machines.index')->with('success', 'Machine added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Machine $machine)
    {
        return view('bakery.machines.show', compact('machine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Machine $machine)
    {
        return view('bakery.machines.edit', compact('machine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|in:operational,maintenance,down',
            'last_maintenance_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $machine->update($validated);
        return redirect()->route('bakery.machines.index')->with('success', 'Machine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('bakery.machines.index')->with('success', 'Machine deleted successfully.');
    }
}

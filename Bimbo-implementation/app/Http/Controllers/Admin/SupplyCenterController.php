<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyCenter;
use Illuminate\Http\Request;

class SupplyCenterController extends Controller
{
    public function index()
    {
        $centers = SupplyCenter::all();
        return view('admin.supply_centers.index', compact('centers'));
    }
    public function create()
    {
        return view('admin.supply_centers.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'required_role' => 'nullable|string|max:255',
            'shift_time' => 'nullable|string|max:255',
            'required_staff_count' => 'required|integer|min:1',
        ]);
        $center = SupplyCenter::create($validated);
        return redirect()->route('admin.supply_centers.index')->with('success', 'Supply Center created successfully.');
    }
    public function edit(SupplyCenter $center)
    {
        return view('admin.supply_centers.edit', compact('center'));
    }
    public function update(Request $request, SupplyCenter $center)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'required_role' => 'nullable|string|max:255',
            'shift_time' => 'nullable|string|max:255',
            'required_staff_count' => 'required|integer|min:1',
        ]);
        $center->update($validated);
        return redirect()->route('admin.supply_centers.index')->with('success', 'Supply Center updated successfully.');
    }
}

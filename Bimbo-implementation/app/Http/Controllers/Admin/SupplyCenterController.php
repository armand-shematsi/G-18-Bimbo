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
        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'required_role' => 'nullable|string',
        ]);
        SupplyCenter::create($data);
        return redirect()->route('admin.supply_centers.index')->with('success', 'Supply center created!');
    }
    public function edit(SupplyCenter $center)
    {
        return view('admin.supply_centers.edit', compact('center'));
    }
    public function update(Request $request, SupplyCenter $center)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'required_role' => 'nullable|string',
        ]);
        $center->update($data);
        return redirect()->route('admin.supply_centers.index')->with('success', 'Supply center updated!');
    }
}

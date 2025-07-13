<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplyCenter;
use Illuminate\Http\Request;

class SupplyCenterController extends Controller
{
    public function index()
    {
        return response()->json(SupplyCenter::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'required_role' => 'required|string|max:255',
        ]);
        $center = SupplyCenter::create($validated);
        return response()->json($center, 201);
    }

    public function show($id)
    {
        $center = SupplyCenter::findOrFail($id);
        return response()->json($center);
    }

    public function update(Request $request, $id)
    {
        $center = SupplyCenter::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
            'required_role' => 'sometimes|required|string|max:255',
        ]);
        $center->update($validated);
        return response()->json($center);
    }

    public function destroy($id)
    {
        $center = SupplyCenter::findOrFail($id);
        $center->delete();
        return response()->json(['success' => true]);
    }
} 
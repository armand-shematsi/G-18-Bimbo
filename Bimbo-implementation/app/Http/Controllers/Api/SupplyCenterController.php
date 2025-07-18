<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupplyCenter;
use Illuminate\Http\Request;

class SupplyCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(SupplyCenter::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $center = SupplyCenter::create($request->only(['name', 'location', 'required_role']));
        return response()->json($center, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $center = SupplyCenter::findOrFail($id);
        return response()->json($center);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $center = SupplyCenter::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|nullable|string|max:255',
            'required_role' => 'sometimes|nullable|string|max:255',
            'shift_time' => 'sometimes|nullable|string|max:255',
            'required_staff_count' => 'sometimes|integer|min:1',
        ]);
        $center->update($validated);
        return response()->json($center);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $center = SupplyCenter::findOrFail($id);
        $center->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Assignment::with(['staff', 'supplyCenter'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $assignment = Assignment::create($request->only(['staff_id', 'supply_center_id', 'shift_time', 'status']));
        return response()->json($assignment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $assignment = Assignment::with(['staff', 'supplyCenter'])->findOrFail($id);
        return response()->json($assignment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->update($request->only(['staff_id', 'supply_center_id', 'shift_time', 'status']));
        return response()->json($assignment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

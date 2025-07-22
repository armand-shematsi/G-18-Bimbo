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
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $assignments = Assignment::with(['staff', 'supplyCenter'])
            ->where('assignment_date', $date)
            ->get();
        return response()->json($assignments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(['staff_id', 'supply_center_id', 'shift_time', 'status']);
        $data['assignment_date'] = $request->input('assignment_date', now()->toDateString());

        $assignment = Assignment::create($data);
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
        $data = $request->only(['staff_id', 'supply_center_id', 'shift_time', 'status']);
        if ($request->has('assignment_date')) {
            $data['assignment_date'] = $request->input('assignment_date');
        }
        $assignment->update($data);
        return response()->json($assignment);
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Get the count of filled (Assigned) and total assignments for a specific date
     */
    public function filledCount(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $total = Assignment::where('assignment_date', $date)->count();
        $filled = Assignment::where('assignment_date', $date)
            ->where('status', 'Assigned')
            ->count();
        return response()->json([
            'filled' => $filled,
            'total' => $total
        ]);
    }
}

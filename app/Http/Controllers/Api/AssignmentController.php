<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Staff;
use App\Models\SupplyCenter;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // List all assignments
    public function index()
    {
        $assignments = Assignment::with(['staff', 'supplyCenter'])->get();
        return response()->json($assignments);
    }

    // Store a new assignment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'supply_center_id' => 'required|exists:supply_centers,id',
            'shift_time' => 'required|string',
            'status' => 'required|in:Assigned,Unfilled',
        ]);
        $assignment = Assignment::create($validated);
        return response()->json($assignment, 201);
    }

    // Auto-assign present staff to centers by needed role for a shift
    public function autoAssign(Request $request)
    {
        $shift_time = $request->input('shift_time', '08:00-16:00');
        // Clear previous assignments for this shift
        Assignment::where('shift_time', $shift_time)->delete();
        $centers = SupplyCenter::all();
        $presentStaff = Staff::where('status', 'Present')->get();
        $assignments = [];
        foreach ($presentStaff as $staff) {
            // Find a center that matches the staff's role
            $center = $centers->firstWhere('required_role', $staff->role);
            if ($center) {
                $assignment = Assignment::create([
                    'staff_id' => $staff->id,
                    'supply_center_id' => $center->id,
                    'shift_time' => $shift_time,
                    'status' => 'Assigned',
                ]);
                $assignments[] = $assignment;
            }
        }
        // Optionally, mark unfilled centers
        foreach ($centers as $center) {
            $assignedCount = Assignment::where('supply_center_id', $center->id)->where('shift_time', $shift_time)->count();
            if ($assignedCount == 0) {
                Assignment::create([
                    'staff_id' => null,
                    'supply_center_id' => $center->id,
                    'shift_time' => $shift_time,
                    'status' => 'Unfilled',
                ]);
            }
        }
        return response()->json(Assignment::with(['staff', 'supplyCenter'])->where('shift_time', $shift_time)->get());
    }
}

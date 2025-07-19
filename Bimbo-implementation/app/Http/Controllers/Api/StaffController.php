<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        return response()->json(Staff::all());
    }

    public function store(Request $request)
    {
        $staff = Staff::create($request->only(['name', 'role', 'status']));
        return response()->json($staff, 201);
    }

    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return response()->json($staff);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Present,Absent',
        ]);
        $staff->update($validated);

        // Trigger real-time update for dashboard
        $this->broadcastStaffUpdate($staff);

        return response()->json($staff);
    }

    /**
     * Update only the status of a staff member
     */
    public function updateStatus(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Present,Absent',
        ]);

        $oldStatus = $staff->status;
        $staff->update(['status' => $validated['status']]);

        // Trigger real-time update for dashboard
        $this->broadcastStaffUpdate($staff);

        return response()->json([
            'success' => true,
            'staff' => $staff,
            'old_status' => $oldStatus,
            'new_status' => $validated['status']
        ]);
    }

    /**
     * Get staff on duty count for dashboard
     */
    public function getStaffOnDuty()
    {
        $presentCount = Staff::where('status', 'Present')->count();
        $absentCount = Staff::where('status', 'Absent')->count();
        $totalStaff = Staff::count();
        
        \Illuminate\Support\Facades\Log::info('Staff on duty count', [
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'totalStaff' => $totalStaff
        ]);
        
        return response()->json([
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'totalStaff' => $totalStaff
        ]);
    }

    /**
     * Broadcast staff update to trigger dashboard refresh
     */
    private function broadcastStaffUpdate($staff)
    {
        // This can be extended with Laravel Echo/Pusher for real-time updates
        // For now, we'll use a simple approach
        \Illuminate\Support\Facades\Log::info('Staff status updated', [
            'staff_id' => $staff->id,
            'staff_name' => $staff->name,
            'new_status' => $staff->status
        ]);
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

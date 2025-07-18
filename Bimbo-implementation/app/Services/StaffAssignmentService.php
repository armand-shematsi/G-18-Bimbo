<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use App\Models\SupplyCenter;
use App\Models\Shift;
use App\Models\StaffSupplyCenterAssignment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\StaffAutoAssigned;

class StaffAssignmentService
{
    /**
     * Auto-assign staff to supply centers for a given date.
     * Returns ['success' => bool, 'message' => string, 'assignments' => array]
     */
    public function autoAssignStaff($date)
    {
        $staff = User::all();
        $absentStaffIds = Attendance::where('date', $date)
            ->where('status', 'absent')
            ->pluck('user_id')
            ->toArray();
        $availableStaff = $staff->whereNotIn('id', $absentStaffIds);
        $centers = SupplyCenter::all();
        $shifts = Shift::whereDate('start_time', $date)->get();
        if ($centers->isEmpty()) {
            Log::warning('Auto-assign failed: No supply centers found.');
            return ['success' => false, 'message' => 'No supply centers found.', 'assignments' => []];
        }
        if ($availableStaff->isEmpty()) {
            Log::warning('Auto-assign failed: No available staff for date ' . $date);
            return ['success' => false, 'message' => 'No available staff for this date.', 'assignments' => []];
        }
        // Ensure default supply center exists
        $defaultCenter = SupplyCenter::firstOrCreate(
            ['name' => 'General station'],
            [
                'location' => 'General',
                'required_role' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        // Remove existing assignments for the day
        StaffSupplyCenterAssignment::where('assigned_date', $date)->delete();
        $assignments = [];
        foreach ($staff as $user) {
            // Try to find a matching center by role
            $center = $centers->first(function ($c) use ($user) {
                return $c->required_role && $c->required_role === $user->role;
            });
            if (!$center) {
                $center = $defaultCenter;
            }
            // Create or get a shift for this user and date
            $shift = Shift::updateOrCreate([
                'user_id' => $user->id,
                'supply_center_id' => $center->id,
                'start_time' => $date . ' 08:00:00',
            ], [
                'end_time' => $date . ' 17:00:00',
                'role' => $user->role,
            ]);
            $assignments[] = StaffSupplyCenterAssignment::create([
                'user_id' => $user->id,
                'supply_center_id' => $center->id,
                'shift_id' => $shift->id,
                'status' => 'on_shift',
                'assigned_date' => $date,
            ]);
            // --- Ensure attendance is set to present for this user and date ---
            Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $date,
                ],
                [
                    'status' => 'present',
                ]
            );
        }
        // --- Broadcast event for real-time dashboard updates ---
        event(new StaffAutoAssigned($date));
        return ['success' => true, 'message' => 'Staff auto-assigned to supply centers and shifts successfully!', 'assignments' => $assignments];
    }
}

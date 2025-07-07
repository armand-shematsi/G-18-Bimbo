<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Shift;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkforceController extends Controller
{
    // Supervisor: Assign a task to a worker
    public function assignTask(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);
        $data['assigned_at'] = now();
        $task = Task::create($data);
        return response()->json($task);
    }

    // Worker: Update task progress
    public function updateTaskStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,reassigned',
        ]);
        $task->status = $request->status;
        if ($request->status === 'completed') {
            $task->completed_at = now();
        }
        $task->save();
        return response()->json($task);
    }

    // Supervisor: Get all tasks for a shift or user
    public function getTasks(Request $request)
    {
        $query = Task::query();
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->shift_id) {
            $query->where('shift_id', $request->shift_id);
        }
        return response()->json($query->with(['user', 'shift'])->get());
    }

    // Auto-reassign tasks if a worker is absent
    public function autoReassignAbsentees()
    {
        $today = now()->toDateString();
        $absentUsers = Attendance::where('date', $today)->where('status', 'absent')->pluck('user_id');
        $tasks = Task::whereIn('user_id', $absentUsers)->where('status', '!=', 'completed')->get();
        $availableUsers = User::whereNotIn('id', $absentUsers)->get();
        foreach ($tasks as $task) {
            $newUser = $availableUsers->where('role', $task->user->role)->first();
            if ($newUser) {
                $task->user_id = $newUser->id;
                $task->status = 'reassigned';
                $task->save();
            }
        }
        return response()->json(['reassigned' => $tasks->count()]);
    }

    public function assignment(Request $request)
    {
        $supplyCenters = \App\Models\SupplyCenter::all();
        $selectedCenter = $request->get('supply_center_id');
        $staff = \App\Models\User::where('role', 'staff')->get();
        return view('workforce.assignment', compact('supplyCenters', 'selectedCenter', 'staff'));
    }

    public function assignStaff(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'supply_center_id' => 'nullable|exists:supply_centers,id',
        ]);
        $user = \App\Models\User::findOrFail($request->user_id);
        $user->supply_center_id = $request->supply_center_id;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Staff assignment updated!',
            'supply_center_name' => optional($user->supplyCenter)->name ?? 'Unassigned'
        ]);
    }

    public function shifts(Request $request)
    {
        $shifts = \App\Models\Shift::with(['user', 'supplyCenter'])->orderBy('start_time', 'desc')->get();
        $staff = \App\Models\User::where('role', 'staff')->get();
        $supplyCenters = \App\Models\SupplyCenter::all();
        return view('workforce.shifts', compact('shifts', 'staff', 'supplyCenters'));
    }

    public function storeShift(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'supply_center_id' => 'required|exists:supply_centers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        $user = \App\Models\User::find($request->user_id);
        $shift = \App\Models\Shift::create([
            'user_id' => $request->user_id,
            'supply_center_id' => $request->supply_center_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'role' => $user ? $user->role : 'staff',
        ]);
        $shift->load(['user', 'supplyCenter']);
        return response()->json([
            'success' => true,
            'message' => 'Shift scheduled successfully!',
            'shift' => [
                'id' => $shift->id,
                'staff' => $shift->user->name,
                'center' => $shift->supplyCenter->name,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ]
        ]);
    }

    public function availability(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $supplyCenterId = $request->get('supply_center_id');
        $staffQuery = \App\Models\User::where('role', 'staff');
        if ($supplyCenterId) {
            $staffQuery->where('supply_center_id', $supplyCenterId);
        }
        $staff = $staffQuery->get();
        $availability = $staff->map(function ($user) use ($date) {
            $shift = \App\Models\Shift::where('user_id', $user->id)
                ->whereDate('start_time', $date)
                ->first();
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->where('date', $date)
                ->first();
            if ($attendance && $attendance->status === 'absent') {
                $status = 'Absent';
            } elseif ($shift) {
                $status = 'On Shift';
            } elseif ($attendance && $attendance->status === 'present') {
                $status = 'Available';
            } else {
                $status = 'Unknown';
            }
            return [
                'name' => $user->name,
                'supply_center' => optional($user->supplyCenter)->name ?? '-',
                'status' => $status,
            ];
        });
        $supplyCenters = \App\Models\SupplyCenter::all();
        return view('workforce.availability', compact('availability', 'date', 'supplyCenters', 'supplyCenterId'));
    }

    public function distributionOverview(Request $request)
    {
        return view('workforce.distribution-overview');
    }

    public function overview(Request $request)
    {
        return view('workforce.overview');
    }
}

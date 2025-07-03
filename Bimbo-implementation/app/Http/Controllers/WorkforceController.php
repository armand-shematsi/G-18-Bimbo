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
} 
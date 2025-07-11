<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;

class StaffAvailabilityController extends Controller
{
    // GET /api/staff-availability?date=YYYY-MM-DD
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $staff = User::all();
        $attendance = Attendance::where('date', $date)->get()->keyBy('user_id');
        $availability = $staff->map(function ($user) use ($attendance) {
            $att = $attendance->get($user->id);
            $status = $att ? ($att->status === 'absent' ? 'Absent' : 'Present') : 'Unknown';
            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $status,
            ];
        });
        return response()->json(['availability' => $availability]);
    }

    // POST /api/attendance { user_id, date, status }
    public function setAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);
        Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
            ]
        );
        return response()->json(['success' => true]);
    }
}

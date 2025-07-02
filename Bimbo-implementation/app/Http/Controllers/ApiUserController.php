<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        $query = User::query();
        if ($role) {
            $query->where('role', $role);
        }
        return response()->json($query->get(['id', 'name', 'role']));
    }

    public function staffOnDuty()
    {
        $today = now()->toDateString();
        $staff = \App\Models\User::whereHas('attendances', function ($q) use ($today) {
            $q->where('date', $today)->where('status', 'present');
        })->get(['id', 'name', 'role']);
        return response()->json([
            'count' => $staff->count(),
            'staff' => $staff
        ]);
    }

    public function staffAvailability(Request $request)
    {
        $startOfWeek = $request->query('week') ? \Carbon\Carbon::parse($request->query('week'))->startOfWeek() : now()->startOfWeek();
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i)->toDateString();
        }
        $users = \App\Models\User::all(['id', 'name', 'role']);
        $availability = [];
        foreach ($users as $user) {
            $userAvailability = [];
            foreach ($days as $date) {
                $attendance = $user->attendances()->where('date', $date)->first();
                $userAvailability[$date] = $attendance ? $attendance->status : 'unknown';
            }
            $availability[] = [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'availability' => $userAvailability
            ];
        }
        return response()->json([
            'days' => $days,
            'availability' => $availability
        ]);
    }

    public function workforceAnalytics(Request $request)
    {
        $startOfWeek = $request->query('week') ? \Carbon\Carbon::parse($request->query('week'))->startOfWeek() : now()->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $totalShifts = \App\Models\Shift::whereBetween('start_time', [$startOfWeek, $endOfWeek])->count();
        $filledShifts = \App\Models\Shift::whereBetween('start_time', [$startOfWeek, $endOfWeek])->whereNotNull('user_id')->count();
        $unfilledShifts = $totalShifts - $filledShifts;
        $absences = \App\Models\Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])->where('status', 'absent')->count();
        $overtime = \App\Models\Shift::whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->whereRaw('TIMESTAMPDIFF(HOUR, start_time, end_time) > 8')->count();
        // Breakdown for live charting
        $days = [];
        $shiftsPerDay = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->toDateString();
            $days[] = $date;
            $shiftsPerDay[] = \App\Models\Shift::whereDate('start_time', $date)->count();
        }
        return response()->json([
            'total_shifts' => $totalShifts,
            'filled_shifts' => $filledShifts,
            'unfilled_shifts' => $unfilledShifts,
            'absences' => $absences,
            'overtime' => $overtime,
            'days' => $days,
            'shifts_per_day' => $shiftsPerDay,
        ]);
    }
}

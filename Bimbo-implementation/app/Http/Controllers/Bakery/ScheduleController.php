<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function create()
    {
        $users = \App\Models\User::orderBy('name')->get();
        return view('bakery.schedule.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,afternoon,night',
            'notes' => 'nullable|string|max:255',
        ]);

        // Map shift_type to start and end times
        $shiftTimes = [
            'morning' => ['06:00:00', '14:00:00'],
            'afternoon' => ['14:00:00', '22:00:00'],
            'night' => ['22:00:00', '06:00:00'],
        ];
        $startDate = $validated['shift_date'];
        $type = $validated['shift_type'];
        $startTime = $shiftTimes[$type][0];
        $endTime = $shiftTimes[$type][1];
        $startDateTime = $startDate . ' ' . $startTime;
        // If night shift, end time is next day
        if ($type === 'night') {
            $endDate = \Carbon\Carbon::parse($startDate)->addDay()->toDateString();
        } else {
            $endDate = $startDate;
        }
        $endDateTime = $endDate . ' ' . $endTime;

        // Create the shift
        \App\Models\Shift::create([
            'user_id' => $validated['employee_id'],
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'role' => ucfirst($type) . ' Shift',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bakery.schedule')
            ->with('success', 'Schedule created successfully.');
    }

    public function index(Request $request)
    {
        // Determine the week to display
        $week = $request->query('week');
        if ($week) {
            $startOfWeek = \Carbon\Carbon::parse($week)->startOfWeek();
        } else {
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
        }
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $users = User::orderBy('name')->get();
        $shifts = Shift::whereBetween('start_time', [$startOfWeek, $endOfWeek])->with('user')->get();

        // Group shifts by user and day
        $schedule = [];
        foreach ($users as $user) {
            $schedule[$user->id]['user'] = $user;
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i)->toDateString();
                $shift = $shifts->where('user_id', $user->id)->first(function ($shift) use ($date) {
                    return \Carbon\Carbon::parse($shift->start_time)->toDateString() === $date;
                });
                $schedule[$user->id]['days'][$date] = $shift;
            }
        }

        $prevWeek = $startOfWeek->copy()->subWeek()->toDateString();
        $nextWeek = $startOfWeek->copy()->addWeek()->toDateString();

        return view('bakery.schedule', [
            'schedule' => $schedule,
            'startOfWeek' => $startOfWeek,
            'prevWeek' => $prevWeek,
            'nextWeek' => $nextWeek,
        ]);
    }
} 
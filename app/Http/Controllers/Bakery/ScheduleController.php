<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function create()
    {
        return view('bakery.schedule.create');
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

        // TODO: Implement schedule creation logic

        return redirect()->route('bakery.schedule')
            ->with('success', 'Schedule created successfully.');
    }
} 
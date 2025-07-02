<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::with(['user', 'productionBatch'])->orderBy('start_time', 'desc')->paginate(10);
        return view('bakery.shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $batches = ProductionBatch::orderBy('scheduled_start', 'desc')->get();
        return view('bakery.shifts.create', compact('users', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'production_batch_id' => 'nullable|exists:production_batches,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'role' => 'required|string|max:255',
        ]);
        Shift::create($validated);
        return redirect()->route('bakery.shifts.index')->with('success', 'Shift assigned successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        $shift->load(['user', 'productionBatch']);
        return view('bakery.shifts.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        $users = User::orderBy('name')->get();
        $batches = ProductionBatch::orderBy('scheduled_start', 'desc')->get();
        return view('bakery.shifts.edit', compact('shift', 'users', 'batches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'production_batch_id' => 'nullable|exists:production_batches,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'role' => 'required|string|max:255',
        ]);
        $shift->update($validated);
        return redirect()->route('bakery.shifts.index')->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('bakery.shifts.index')->with('success', 'Shift deleted successfully.');
    }

    /**
     * API: Get currently active staff (on duty)
     */
    public function apiActiveStaff()
    {
        $now = now();
        $activeShifts = Shift::with('user')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->get();
        $staff = $activeShifts->map(function ($shift) {
            return [
                'name' => $shift->user->name,
                'role' => $shift->role,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ];
        });
        return response()->json($staff);
    }
}

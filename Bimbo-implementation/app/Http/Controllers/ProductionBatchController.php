<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use Illuminate\Http\Request;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = ProductionBatch::orderBy('scheduled_start', 'desc')->paginate(10);
        return view('bakery.batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bakery.batches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:planned,active,completed,cancelled',
            'scheduled_start' => 'required|date',
            'actual_start' => 'nullable|date',
            'actual_end' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        ProductionBatch::create($validated);
        return redirect()->route('bakery.batches.index')->with('success', 'Production batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionBatch $batch)
    {
        $batch->load('shifts.user');
        return view('bakery.batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionBatch $batch)
    {
        return view('bakery.batches.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionBatch $batch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:planned,active,completed,cancelled',
            'scheduled_start' => 'required|date',
            'actual_start' => 'nullable|date',
            'actual_end' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $batch->update($validated);
        return redirect()->route('bakery.batches.index')->with('success', 'Production batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionBatch $batch)
    {
        $batch->delete();
        return redirect()->route('bakery.batches.index')->with('success', 'Production batch deleted successfully.');
    }

    /**
     * API: Get production batches with optional filters (status, date, product)
     */
    public function apiIndex(Request $request)
    {
        $query = ProductionBatch::with('shifts.user');
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('date')) {
            $query->whereDate('scheduled_start', $request->input('date'));
        } else {
            $query->whereDate('scheduled_start', now()->toDateString());
        }
        if ($request->has('product')) {
            $query->where('name', 'like', '%' . $request->input('product') . '%');
        }
        $batches = $query->orderBy('scheduled_start', 'desc')->get();
        return response()->json($batches);
    }

    /**
     * API: Store a new production batch (AJAX)
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:planned,active,completed,cancelled',
            'scheduled_start' => 'required|date',
            'actual_start' => 'nullable|date',
            'actual_end' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $batch = ProductionBatch::create($validated);
        return response()->json(['success' => true, 'batch' => $batch]);
    }

    /**
     * API: Update a production batch (AJAX)
     */
    public function apiUpdate(Request $request, ProductionBatch $batch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:planned,active,completed,cancelled',
            'scheduled_start' => 'required|date',
            'actual_start' => 'nullable|date',
            'actual_end' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $batch->update($validated);
        return response()->json(['success' => true, 'batch' => $batch]);
    }

    /**
     * API: Update only the status of a batch (AJAX)
     */
    public function apiUpdateStatus(Request $request, ProductionBatch $batch)
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,active,completed,cancelled',
        ]);
        $batch->update(['status' => $validated['status']]);
        return response()->json(['success' => true, 'batch' => $batch]);
    }

    /**
     * Assign a shift to a batch (AJAX)
     */
    public function assignShift(Request $request, $batchId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        $shift = \App\Models\Shift::create([
            'production_batch_id' => $batchId,
            'user_id' => $request->user_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'role' => 'staff',
        ]);
        return response()->json(['success' => true, 'shift' => $shift]);
    }
}

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
}

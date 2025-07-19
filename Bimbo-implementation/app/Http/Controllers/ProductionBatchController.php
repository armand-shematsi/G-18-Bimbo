<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Auto-complete batches whose actual_end is in the past
        \App\Models\ProductionBatch::where('status', 'active')
            ->whereNotNull('actual_end')
            ->where('actual_end', '<', now())
            ->update(['status' => 'completed']);
        $batches = \App\Models\ProductionBatch::orderBy('scheduled_start', 'desc')->get();
        return view('bakery.batches.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all bread product names for the dropdown
        $breadProducts = \App\Models\Product::where(function ($q) {
            $q->where('name', 'like', '%bread%')
                ->orWhere('name', 'like', '%baguette%')
                ->orWhere('name', 'like', '%brioche%')
                ->orWhere('name', 'like', '%focaccia%')
                ->orWhere('name', 'like', '%potato%')
                ->orWhere('name', 'like', '%honey oat%')
                ->orWhere('name', 'like', '%challah%')
                ->orWhere('name', 'like', '%pita%')
                ->orWhere('name', 'like', '%bagel%')
                ->orWhere('name', 'like', '%muffin%')
                ->orWhere('name', 'like', '%roll%')
                ->orWhere('name', 'like', '%cinnamon%')
                ->orWhere('name', 'like', '%gluten%')
                ->orWhere('name', 'like', '%rustic%')
                ->orWhere('name', 'like', '%ciabatta%');
        })->orderBy('name')->pluck('name')->toArray();
        // By default, show all lines
        $productionLines = \App\Models\ProductionLine::all();
        $products = \App\Models\Product::all();
        return view('bakery.batches.create', compact('productionLines', 'breadProducts', 'products'));
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
            'quantity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'production_line_id' => 'nullable|string|max:10',
            'product_id' => 'required|exists:products,id',
        ]);
        // Convert UTC datetimes to Africa/Nairobi timezone
        $validated['scheduled_start'] = Carbon::parse($validated['scheduled_start'])->setTimezone('Africa/Nairobi');
        if (!empty($validated['actual_start'])) {
            $validated['actual_start'] = Carbon::parse($validated['actual_start'])->setTimezone('Africa/Nairobi');
        }
        if (!empty($validated['actual_end'])) {
            $validated['actual_end'] = Carbon::parse($validated['actual_end'])->setTimezone('Africa/Nairobi');
        }
        $batch = ProductionBatch::create($validated);
        // Inventory adjustment if completed
        if ($batch->status === 'completed') {
            $inventory = \App\Models\Inventory::where('product_id', $batch->product_id)
                ->where('location', 'bakery')
                ->where('item_type', 'finished_good')
                ->first();
            if ($inventory) {
                $inventory->quantity += $batch->quantity;
                $inventory->save();
            } else {
                $product = $batch->product;
                \App\Models\Inventory::create([
                    'item_name' => $product ? $product->name : 'Batch Product',
                    'quantity' => $batch->quantity,
                    'unit_price' => $product ? ($product->unit_price ?? 0) : 0,
                    'unit' => 'unit',
                    'item_type' => 'finished_good',
                    'reorder_level' => 0,
                    'location' => 'bakery',
                    'product_id' => $batch->product_id,
                ]);
            }
        }
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
        $breadProducts = \App\Models\Product::where(function ($q) {
            $q->where('name', 'like', '%bread%')
                ->orWhere('name', 'like', '%baguette%')
                ->orWhere('name', 'like', '%brioche%')
                ->orWhere('name', 'like', '%focaccia%')
                ->orWhere('name', 'like', '%potato%')
                ->orWhere('name', 'like', '%honey oat%')
                ->orWhere('name', 'like', '%challah%')
                ->orWhere('name', 'like', '%pita%')
                ->orWhere('name', 'like', '%bagel%')
                ->orWhere('name', 'like', '%muffin%')
                ->orWhere('name', 'like', '%roll%')
                ->orWhere('name', 'like', '%cinnamon%')
                ->orWhere('name', 'like', '%gluten%')
                ->orWhere('name', 'like', '%rustic%')
                ->orWhere('name', 'like', '%ciabatta%');
        })->orderBy('name')->pluck('name')->toArray();
        // By default, show all lines
        $productionLines = \App\Models\ProductionLine::all();
        $products = \App\Models\Product::all();
        return view('bakery.batches.edit', compact('batch', 'productionLines', 'breadProducts', 'products'));
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
            'quantity' => 'nullable|integer|min:0',
            'production_line_id' => 'nullable|string|max:10',
            'product_id' => 'required|exists:products,id',
        ]);
        // Convert UTC datetimes to Africa/Nairobi timezone
        $validated['scheduled_start'] = Carbon::parse($validated['scheduled_start'])->setTimezone('Africa/Nairobi');
        if (!empty($validated['actual_start'])) {
            $validated['actual_start'] = Carbon::parse($validated['actual_start'])->setTimezone('Africa/Nairobi');
        }
        if (!empty($validated['actual_end'])) {
            $validated['actual_end'] = Carbon::parse($validated['actual_end'])->setTimezone('Africa/Nairobi');
        }
        $wasCompleted = $batch->status === 'completed';
        $batch->update($validated);
        // Inventory adjustment if status changed to completed
        if (!$wasCompleted && $batch->status === 'completed') {
            $inventory = \App\Models\Inventory::where('product_id', $batch->product_id)
                ->where('location', 'bakery')
                ->where('item_type', 'finished_good')
                ->first();
            if ($inventory) {
                $inventory->quantity += $batch->quantity;
                $inventory->save();
            } else {
                $product = $batch->product;
                \App\Models\Inventory::create([
                    'item_name' => $product ? $product->name : 'Batch Product',
                    'quantity' => $batch->quantity,
                    'unit_price' => $product ? ($product->unit_price ?? 0) : 0,
                    'unit' => 'unit',
                    'item_type' => 'finished_good',
                    'reorder_level' => 0,
                    'location' => 'bakery',
                    'product_id' => $batch->product_id,
                ]);
            }
        }
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
        // Convert UTC datetimes to Africa/Nairobi timezone
        $validated['scheduled_start'] = Carbon::parse($validated['scheduled_start'])->setTimezone('Africa/Nairobi');
        if (!empty($validated['actual_start'])) {
            $validated['actual_start'] = Carbon::parse($validated['actual_start'])->setTimezone('Africa/Nairobi');
        }
        if (!empty($validated['actual_end'])) {
            $validated['actual_end'] = Carbon::parse($validated['actual_end'])->setTimezone('Africa/Nairobi');
        }
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
        // Convert UTC datetimes to Africa/Nairobi timezone
        $validated['scheduled_start'] = Carbon::parse($validated['scheduled_start'])->setTimezone('Africa/Nairobi');
        if (!empty($validated['actual_start'])) {
            $validated['actual_start'] = Carbon::parse($validated['actual_start'])->setTimezone('Africa/Nairobi');
        }
        if (!empty($validated['actual_end'])) {
            $validated['actual_end'] = Carbon::parse($validated['actual_end'])->setTimezone('Africa/Nairobi');
        }
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

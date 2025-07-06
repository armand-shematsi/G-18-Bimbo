<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\ProductionBatch;

class ProductionController extends Controller
{
    public function start()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $products = \App\Models\Product::all();
        return view('bakery.production.start', compact('ingredients', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'scheduled_start' => 'required|date',
            'notes' => 'nullable|string',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $batch = ProductionBatch::create([
            'name' => $validated['name'],
            'status' => 'active',
            'scheduled_start' => $validated['scheduled_start'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Attach ingredients with quantities
        $ingredientData = [];
        foreach ($validated['ingredients'] as $ingredient) {
            $ingredientData[$ingredient['id']] = ['quantity_used' => $ingredient['quantity']];
        }
        $batch->ingredients()->attach($ingredientData);

        return redirect()->route('bakery.batches.show', $batch)->with('success', 'Production started successfully.');
    }
}

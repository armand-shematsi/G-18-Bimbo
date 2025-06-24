<?php

namespace App\Http\Controllers\Bakery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function start()
    {
        return view('bakery.production.start');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'line_id' => 'required|exists:production_lines,id',
        ]);

        // TODO: Implement production start logic

        return redirect()->route('bakery.production')
            ->with('success', 'Production started successfully.');
    }
} 
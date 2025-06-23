<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display the supplier's orders.
     */
    public function index()
    {
        return view('supplier.orders.index');
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('supplier.orders.create');
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bakery_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // TODO: Store the order in the database

        return redirect()->route('supplier.orders')
            ->with('success', 'Order created successfully.');
    }
} 
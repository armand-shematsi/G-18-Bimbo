<?php

namespace App\Http\Controllers;

use App\Models\OrderReturn;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderReturnController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);
        $order = Order::findOrFail($orderId);
        OrderReturn::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        return redirect()->route('retail.orders.show', $order->id)->with('success', 'Return request submitted!');
    }

    public function index()
    {
        $returns = OrderReturn::where('user_id', Auth::id())->latest()->get();
        return view('retail.returns.index', compact('returns'));
    }

    public function show($id)
    {
        $return = OrderReturn::where('user_id', Auth::id())->findOrFail($id);
        return view('retail.returns.show', compact('return'));
    }
} 
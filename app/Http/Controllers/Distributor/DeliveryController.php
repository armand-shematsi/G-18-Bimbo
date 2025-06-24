<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('distributor.deliveries.index');
    }

    public function confirm()
    {
        return view('distributor.deliveries.confirm');
    }

    public function storeConfirmation(Request $request)
    {
        // Logic to store the delivery confirmation
        return redirect()->route('distributor.deliveries')->with('success', 'Delivery confirmed successfully.');
    }
} 
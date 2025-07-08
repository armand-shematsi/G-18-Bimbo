<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        // Return a view for placing a new order
        return view('orders.create');
    }
}

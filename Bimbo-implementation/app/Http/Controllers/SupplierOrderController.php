<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    public function index() {
        // Return a view for supplier orders
        return view('supplier.orders.index');
    }
}

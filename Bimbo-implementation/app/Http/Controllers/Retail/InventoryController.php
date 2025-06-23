<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('retail.inventory');
    }
} 
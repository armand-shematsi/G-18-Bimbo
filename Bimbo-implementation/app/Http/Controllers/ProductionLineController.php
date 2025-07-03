<?php

namespace App\Http\Controllers;

use App\Models\ProductionLine;

class ProductionLineController extends Controller
{
    public function index()
    {
        return response()->json(ProductionLine::all());
    }
} 
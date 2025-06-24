<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;

class ForecastController extends Controller
{
    public function index()
    {
        return view('retail.forecast');
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\Shift;

class ApiShiftController extends Controller
{
    public function index()
    {
        return response()->json(Shift::all(['id', 'name']));
    }
}

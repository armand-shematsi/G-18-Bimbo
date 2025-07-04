<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerSegmentController extends Controller
{
    public function index()
    {
        $segments = DB::table('customer_segments')->paginate(20);
        return view('customer_segments.index', compact('segments'));
    }
}

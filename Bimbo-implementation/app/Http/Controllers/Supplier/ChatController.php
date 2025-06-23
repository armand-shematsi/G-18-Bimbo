<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display the supplier's chat interface.
     */
    public function index()
    {
        return view('supplier.chat.index');
    }
} 
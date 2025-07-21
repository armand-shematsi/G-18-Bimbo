<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you could store the support request in a table, send an email, etc.
        // For now, just redirect back with a success message
        return back()->with('success', 'Support request submitted!');
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|min:3',
            'message' => 'required|string|min:5',
            'order_id' => 'nullable|exists:orders,id',
        ]);
        SupportRequest::create([
            'order_id' => $request->order_id,
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);
        return back()->with('success', 'Support request submitted!');
    }

    public function index()
    {
        $requests = SupportRequest::where('user_id', Auth::id())->latest()->get();
        return view('retail.support.index', compact('requests'));
    }

    public function show($id)
    {
        $request = SupportRequest::where('user_id', Auth::id())->findOrFail($id);
        return view('retail.support.show', compact('request'));
    }
} 
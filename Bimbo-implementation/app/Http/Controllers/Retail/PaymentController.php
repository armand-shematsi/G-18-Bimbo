<?php

namespace App\Http\Controllers\Retail;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Confirm payment (mark as paid)
    public function confirm($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->save();
        return redirect()->back()->with('success', 'Payment confirmed!');
    }

    // Refund payment (stub)
    public function refund($id)
    {
        // Implement refund logic here
        return redirect()->back()->with('info', 'Refund feature coming soon!');
    }
} 
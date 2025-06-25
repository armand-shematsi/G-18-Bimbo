<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $suppliers = User::where('role', 'supplier')->get();
        $currentChat = request('user_id') ? User::findOrFail(request('user_id')) : null;

        if ($currentChat) {
            $messages = Message::where(function($query) use ($currentChat) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $currentChat->id);
            })->orWhere(function($query) use ($currentChat) {
                $query->where('sender_id', $currentChat->id)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();

            // Mark messages as read
            Message::where('sender_id', $currentChat->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            $messages = collect();
        }

        return view('customer.chat.index', compact('suppliers', 'currentChat', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully');
    }
}

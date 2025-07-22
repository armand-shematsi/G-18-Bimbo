<?php

namespace App\Http\Controllers\Retail;

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

        $unreadCount = 0;
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

            // Count unread messages for badge
            $unreadCount = Message::where('receiver_id', Auth::id())
                ->where('sender_id', $currentChat->id)
                ->where('is_read', false)
                ->count();
        } else {
            $messages = collect();
        }

        // Total unread messages for navbar badge
        $totalUnread = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('retail.chat.index', compact('suppliers', 'currentChat', 'messages', 'unreadCount', 'totalUnread'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully');
    }

    public function getMessages(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $messages = Message::where(function($query) use ($request) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $request->user_id);
        })->orWhere(function($query) use ($request) {
            $query->where('sender_id', $request->user_id)
                ->where('receiver_id', Auth::id());
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc')
          ->get();

        return response()->json($messages);
    }
}
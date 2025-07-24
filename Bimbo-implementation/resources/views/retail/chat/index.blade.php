
@extends('layouts.retail-manager')

@section('header')
<div class="flex items-center gap-3">
    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z" />
    </svg>
    <span class="text-2xl font-bold text-gray-900">Chat with Suppliers</span>
</div>
@endsection

@section('content')
<div class="py-4 min-h-[80vh] bg-gradient-to-br from-blue-100 via-white to-indigo-100">
    <div class="max-w-5xl mx-auto sm:px-2 lg:px-2">
        <div class="bg-white/90 backdrop-blur-md overflow-hidden shadow-2xl sm:rounded-2xl border border-gray-100">
            <div class="p-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50 border-b border-gray-200">
                <div class="flex h-[600px]">
                    <!-- Suppliers List -->
                    <div class="w-1/4 border-r border-gray-200 overflow-y-auto bg-gradient-to-b from-blue-100 to-white">
                        <div class="p-2">
                            <h3 class="text-lg font-semibold text-blue-700 mb-2">Suppliers</h3>
                            <div class="space-y-1">
                                @foreach($suppliers as $supplier)
                                    <a href="{{ route('retail.chat.index', ['user_id' => $supplier->id]) }}" 
                                       class="flex items-center gap-2 p-2 rounded-xl transition-all duration-150 {{ request('user_id') == $supplier->id ? 'bg-blue-100 text-blue-800 font-bold shadow' : 'hover:bg-blue-50' }}">
                                        <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-base shadow-inner border-2 border-white">
                                            {{ strtoupper(substr($supplier->name,0,1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <span>{{ $supplier->name }}</span>
                                            @if(request('user_id') != $supplier->id && $currentChat && $supplier->id == $currentChat->id && $unreadCount > 0)
                                                <span class="inline-block ml-2 px-2 py-1 text-xs font-bold text-white bg-red-600 rounded-full align-top animate-pulse">{{ $unreadCount }}</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Chat Area -->
                    <div class="w-3/4 flex flex-col bg-gradient-to-br from-white to-blue-50">
                        @if($currentChat)
                            <!-- Chat Header -->
                            <div class="p-3 border-b border-gray-200 flex items-center gap-2 bg-white/80 sticky top-0 z-10">
                                <div class="w-9 h-9 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-lg shadow-inner border-2 border-white">
                                    {{ strtoupper(substr($currentChat->name,0,1)) }}
                                </div>
                                <h3 class="text-base font-semibold text-gray-700">{{ $currentChat->name }}</h3>
                            </div>
                            <!-- Messages -->
                            <div class="flex-1 p-3 overflow-y-auto space-y-3" id="messages-container" style="background: linear-gradient(135deg, #f0f7ff 0%, #fff 100%);">
                                @foreach($messages as $message)
                                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-xs px-3 py-2 rounded-2xl shadow
                                            {{ $message->sender_id === Auth::id() ? 'bg-blue-600 text-white rounded-br-none animate-fade-in-right' : 'bg-gray-200 text-gray-900 rounded-bl-none animate-fade-in-left' }}">
                                            <div class="text-xs font-semibold mb-1 flex items-center gap-1">
                                                @if($message->sender_id !== Auth::id())
                                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 text-blue-700 font-bold text-xs shadow-inner border-2 border-white">
                                                        {{ strtoupper(substr($message->sender->name ?? 'U',0,1)) }}
                                                    </span>
                                                @endif
                                                {{ $message->sender_id === Auth::id() ? 'You' : ($message->sender->name ?? 'User') }}
                                                <span class="text-[10px] text-gray-300 ml-1">{{ $message->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="text-sm break-words">{{ $message->content }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Typing Indicator -->
                            <div id="typing-indicator" class="flex items-center gap-2 px-3 py-1 text-sm text-gray-500" style="display:none;">
                                <svg class="w-5 h-5 text-blue-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <circle cx="12" cy="16" r="1" fill="currentColor"/>
                                    <circle cx="9" cy="16" r="1" fill="currentColor"/>
                                    <circle cx="15" cy="16" r="1" fill="currentColor"/>
                                </svg>
                                Typing...
                            </div>
                            <!-- Toast Notification -->
                            <div id="toast" class="fixed bottom-6 right-6 z-50 hidden px-6 py-3 bg-green-600 text-white rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-up">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Message sent!</span>
                            </div>
                            <!-- Message Input -->
                            <div class="p-3 border-t border-gray-200 bg-white/80">
                                <form action="{{ route('retail.chat.send') }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $currentChat->id }}">
                                    <input type="text" name="content" class="flex-1 rounded-full border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2 text-sm bg-white/90" placeholder="Type your message..." autocomplete="off" required>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center gap-1 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Send
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center text-gray-400 text-lg">
                                <svg class="w-10 h-10 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8z" />
                                </svg>
                                Select a supplier to start chatting
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
@keyframes fade-in-left {
    from { opacity: 0; transform: translateX(-30px);}
    to { opacity: 1; transform: translateX(0);}
}
@keyframes fade-in-right {
    from { opacity: 0; transform: translateX(30px);}
    to { opacity: 1; transform: translateX(0);}
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px);}
    to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in-left { animation: fade-in-left 0.5s; }
.animate-fade-in-right { animation: fade-in-right 0.5s; }
.animate-fade-in-up { animation: fade-in-up 0.4s; }
.bg-white\/90 { background-color: rgba(255,255,255,0.90);}
.backdrop-blur-md { backdrop-filter: blur(8px);}
</style>

<script>
window.addEventListener('DOMContentLoaded', function() {
    var chat = document.getElementById('messages-container');
    if(chat) chat.scrollTop = chat.scrollHeight;

    // Typing indicator logic
    var input = document.querySelector('input[name="content"]');
    var indicator = document.getElementById('typing-indicator');
    if(input && indicator) {
        input.addEventListener('input', function() {
            if(this.value.length > 0) {
                indicator.style.display = '';
            } else {
                indicator.style.display = 'none';
            }
        });
        input.addEventListener('blur', function() {
            indicator.style.display = 'none';
        });
    }

    // Toast notification on message send
    var form = document.querySelector('form[action="{{ route('retail.chat.send') }}"]');
    var toast = document.getElementById('toast');
    if(form && toast) {
        form.addEventListener('submit', function() {
            setTimeout(function() {
                toast.classList.remove('hidden');
                setTimeout(function() {
                    toast.classList.add('hidden');
                }, 3000);
            }, 100);
        });
    }
});
</script>
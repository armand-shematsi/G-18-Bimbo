@extends('layouts.supplier')

@section('header')
    Chat with Retailers
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex h-[600px]">
                    <!-- Retailers List -->
                    <div class="w-1/4 border-r border-gray-200 overflow-y-auto">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Retail Managers</h3>
                            <div class="space-y-2">
                                @forelse($retailers as $retailer)
                                    <a href="{{ route('supplier.chat.index', ['user_id' => $retailer->id]) }}" 
                                       class="block p-3 rounded-lg {{ request('user_id') == $retailer->id ? 'bg-primary-50 text-primary-700 font-bold' : 'hover:bg-gray-50' }}">
                                        <div class="font-medium">{{ $retailer->name }}</div>
                                        <div class="text-xs text-gray-500">Role: {{ $retailer->role }}</div>
                                    </a>
                                @empty
                                    <div class="text-gray-500">No retail managers found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="w-3/4 flex flex-col">
                        @if($currentChat)
                            <!-- Chat Header -->
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-700">{{ $currentChat->name }}</h3>
                            </div>

                            <!-- Messages -->
                            <div class="flex-1 p-4 overflow-y-auto" id="messages-container">
                                @forelse($messages as $message)
                                    <div class="mb-4 {{ $message->sender_id === Auth::id() ? 'text-right' : 'text-left' }}">
                                        <div class="inline-block max-w-xl rounded-lg px-4 py-2 {{ $message->sender_id === Auth::id() ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800' }}">
                                            <p class="text-sm">{{ $message->content }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('M j, Y H:i') }}</p>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-400">No messages yet. Start the conversation!</div>
                                @endforelse
                            </div>

                            <!-- Message Input -->
                            <div class="p-4 border-t border-gray-200">
                                <form action="{{ route('supplier.chat.send') }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $currentChat->id }}">
                                    <input type="text" name="content" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Type your message..." required>
                                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                        Send
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex-1 flex items-center justify-center text-gray-500">
                                Select a retailer to start chatting
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
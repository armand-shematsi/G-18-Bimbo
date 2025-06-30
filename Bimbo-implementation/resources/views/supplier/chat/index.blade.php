@extends('layouts.supplier')

@section('header')
    <div class="flex items-center space-x-3">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2M15 3h-6a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2V5a2 2 0 00-2-2z" />
        </svg>
        <span class="text-2xl font-bold text-gray-900">Supplier Chat</span>
    </div>
@endsection

@section('content')
<div class="py-10 bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden flex h-[700px] border border-gray-200">
            <!-- Retailers & Customers List -->
            <div class="w-1/4 bg-gradient-to-b from-blue-100 to-purple-100 border-r border-gray-200 overflow-y-auto p-0">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-blue-700 mb-4">Retail Managers</h3>
                    <div class="space-y-2">
                        @forelse($retailers as $retailer)
                            <a href="{{ route('supplier.chat.index', ['user_id' => $retailer->id]) }}"
                               class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-150 {{ request('user_id') == $retailer->id ? 'bg-blue-200 text-blue-900 font-bold shadow' : 'hover:bg-blue-50' }}">
                                <div class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($retailer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ optional($retailer)->name }}</div>
                                    <div class="text-xs text-gray-500">Role: {{ $retailer->role }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-gray-500">No retail managers found.</div>
                        @endforelse
                    </div>
                    <h3 class="text-lg font-bold text-green-700 mt-8 mb-4">Customers</h3>
                    <div class="space-y-2">
                        @forelse($customers as $customer)
                            <a href="{{ route('supplier.chat.index', ['user_id' => $customer->id]) }}"
                               class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-150 {{ request('user_id') == $customer->id ? 'bg-green-200 text-green-900 font-bold shadow' : 'hover:bg-green-50' }}">
                                <div class="w-10 h-10 rounded-full bg-green-400 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ optional($customer)->name }}</div>
                                    <div class="text-xs text-gray-500">Role: {{ $customer->role }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-gray-500">No customers found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-3/4 flex flex-col bg-white">
                @if($currentChat)
                    <!-- Chat Header -->
                    <div class="sticky top-0 z-10 p-6 bg-gradient-to-r from-blue-100 to-purple-100 border-b border-gray-200 flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full bg-blue-400 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($currentChat->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ optional($currentChat)->name }}</h3>
                            <div class="text-xs text-gray-500">{{ ucfirst($currentChat->role) }}</div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 p-6 overflow-y-auto bg-gradient-to-br from-white to-blue-50" id="messages-container">
                        @forelse($messages as $message)
                            <div class="mb-4 flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs md:max-w-md lg:max-w-lg rounded-2xl px-5 py-3 shadow
                                    {{ $message->sender_id === Auth::id()
                                        ? 'bg-blue-600 text-white rounded-br-none'
                                        : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
                                    <p class="text-base">{{ $message->content }}</p>
                                    <span class="block text-xs mt-2 {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-gray-500' }}">
                                        {{ $message->created_at->format('M j, Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-400">No messages yet. Start the conversation!</div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    <div class="p-6 border-t border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50">
                        <form action="{{ route('supplier.chat.send') }}" method="POST" class="flex space-x-3">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $currentChat->id }}">
                            <input type="text" name="content" class="flex-1 rounded-full border-gray-300 shadow focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-4 py-2 text-gray-700 bg-white" placeholder="Type your message..." required>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-semibold shadow hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Send
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-400 bg-gradient-to-br from-white to-blue-50">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-blue-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2M15 3h-6a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2V5a2 2 0 00-2-2z" />
                            </svg>
                            <p class="text-lg">Select a retailer or customer to start chatting</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

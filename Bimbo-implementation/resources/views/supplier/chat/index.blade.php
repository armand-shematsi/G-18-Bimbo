@extends('layouts.dashboard')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat with Bakery') }}
        </h2>
    </div>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex h-[600px]">
                <!-- Chat list -->
                <div class="w-1/4 border-r border-gray-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-4">Conversations</h3>
                        <div class="space-y-2">
                            <div class="text-center text-gray-500">
                                No active conversations
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat messages -->
                <div class="w-3/4 flex flex-col">
                    <div class="flex-1 p-4 overflow-y-auto">
                        <div class="text-center text-gray-500">
                            Select a conversation to start chatting
                        </div>
                    </div>
                    <div class="border-t border-gray-200 p-4">
                        <form class="flex space-x-4">
                            <input type="text" class="flex-1 rounded-lg border-gray-300" placeholder="Type your message...">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
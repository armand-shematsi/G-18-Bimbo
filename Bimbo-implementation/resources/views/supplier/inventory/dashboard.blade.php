@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                📊 Inventory Dashboard
            </h2>
            <p class="text-gray-600 mt-1">Monitor your inventory performance and stock levels</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('supplier.inventory.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Item
            </a>
            <a href="{{ route('supplier.inventory.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View All
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- All cards and summary/stat sections removed as requested. -->
@endsection

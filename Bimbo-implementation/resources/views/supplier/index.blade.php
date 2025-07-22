@extends('layouts.supplier')

@section('content')
    <div class="w-full flex justify-end mt-4">
        <a href="/supplier/inventory/dashboard" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Go to Inventory Dashboard
        </a>
    </div>
    <div class="w-full flex justify-end mt-4">
        <a href="{{ route('reports.downloads') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Reports
        </a>
    </div>
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Supplier Dashboard') }}
    </h2>
@endsection

{{-- Optionally, keep the welcome message below if you want --}}
{{--
@section('content')
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-bold mb-4">Welcome, Supplier!</h3>
        </div>
    </div>
@endsection
--}}

@extends('layouts.retail-manager')

@section('header')
    Support Request #{{ $request->id }}
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow p-8">
        <h2 class="text-xl font-bold text-blue-700 mb-4">Support Request Details</h2>
        <div class="mb-4">
            <strong>Subject:</strong> {{ $request->subject }}
        </div>
        <div class="mb-4">
            <strong>Message:</strong> {{ $request->message }}
        </div>
        <div class="mb-4">
            <strong>Status:</strong> <span class="font-semibold">{{ ucfirst($request->status) }}</span>
        </div>
        @if($request->response)
        <div class="mb-4">
            <strong>Response:</strong> {{ $request->response }}
        </div>
        @endif
        <a href="{{ route('retail.support.index') }}" class="text-blue-700 font-bold hover:underline">&larr; Back to Support</a>
    </div>
</div>
@endsection 
@extends('layouts.retail-manager')

@section('header')
    Return Request #{{ $return->id }}
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow p-8">
        <h2 class="text-xl font-bold text-emerald-700 mb-4">Return Request Details</h2>
        <div class="mb-4">
            <strong>Order ID:</strong> {{ $return->order_id }}
        </div>
        <div class="mb-4">
            <strong>Reason:</strong> {{ $return->reason }}
        </div>
        <div class="mb-4">
            <strong>Status:</strong> <span class="font-semibold">{{ ucfirst($return->status) }}</span>
        </div>
        @if($return->response)
        <div class="mb-4">
            <strong>Response:</strong> {{ $return->response }}
        </div>
        @endif
        <a href="{{ route('retail.returns.index') }}" class="text-emerald-700 font-bold hover:underline">&larr; Back to Returns</a>
    </div>
</div>
@endsection 
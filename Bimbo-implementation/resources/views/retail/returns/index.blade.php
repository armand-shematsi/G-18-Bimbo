@extends('layouts.retail-manager')

@section('header')
    My Return Requests
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold text-emerald-700 mb-6">Return Requests</h2>
    <div class="bg-white rounded-2xl shadow p-6">
        <table class="min-w-full divide-y divide-emerald-200">
            <thead class="bg-emerald-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Order ID</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Reason</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                <tr>
                    <td class="px-4 py-2">{{ $return->order_id }}</td>
                    <td class="px-4 py-2">{{ Str::limit($return->reason, 40) }}</td>
                    <td class="px-4 py-2">{{ ucfirst($return->status) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('retail.returns.show', $return->id) }}" class="text-emerald-700 font-bold hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-2 text-gray-500 text-center">No return requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 
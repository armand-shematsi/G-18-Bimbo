@extends('layouts.retail-manager')

@section('header')
    My Support Requests
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold text-blue-700 mb-6">Support Requests</h2>
    <div class="bg-white rounded-2xl shadow p-6">
        <table class="min-w-full divide-y divide-blue-200">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Subject</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td class="px-4 py-2">{{ $request->subject }}</td>
                    <td class="px-4 py-2">{{ ucfirst($request->status) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('retail.support.show', $request->id) }}" class="text-blue-700 font-bold hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-2 text-gray-500 text-center">No support requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 
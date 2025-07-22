@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-extrabold text-2xl text-blue-900 leading-tight">
            {{ __('Stock In History') }}
        </h2>
        <a href="{{ route('supplier.stockin.create') }}" class="add-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold uppercase tracking-wide">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Stock In
        </a>
    </div>
@endsection

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 100%);
    }
    .add-btn {
        background: linear-gradient(90deg, #38bdf8 0%, #6366f1 100%);
        color: #fff;
        font-weight: bold;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(59,130,246,0.10);
        transition: background 0.2s, transform 0.2s;
    }
    .add-btn:hover {
        background: linear-gradient(90deg, #6366f1 0%, #38bdf8 100%);
        transform: scale(1.04);
    }
    .glass-table {
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1.5px solid #bae6fd;
        box-shadow: 0 2px 12px 0 rgba(59,130,246,0.08);
        background: #fff;
    }
    .glass-table th {
        position: sticky;
        top: 0;
        background: linear-gradient(90deg, #e0e7ff 0%, #f0f9ff 100%);
        z-index: 1;
        font-weight: 800;
        color: #3730a3;
        box-shadow: 0 2px 8px rgba(59,130,246,0.04);
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
    }
    .glass-table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .glass-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .glass-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .glass-table tr:hover {
        background: #e0e7ff !important;
        box-shadow: 0 4px 16px 0 rgba(99,102,241,0.10);
        transform: scale(1.01);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: #64748b;
    }
    .empty-state svg {
        margin: 0 auto 1rem auto;
        display: block;
    }
    .empty-state h3 {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e293b;
    }
</style>
    <div class="glass-table overflow-hidden">
        <div class="p-6 text-gray-900">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Quantity Received</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Received Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockIns as $stockIn)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-blue-900">{{ $stockIn->inventory->item_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">{{ $stockIn->quantity_received }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">{{ $stockIn->received_at ? \Carbon\Carbon::parse($stockIn->received_at)->format('Y-m-d H:i') : '' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3>No stock-in records found.</h3>
                                <p class="mt-1 text-sm text-gray-500">Click "Add Stock In" to record your first stock-in entry.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.supplier')

@section('header')
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-extrabold text-2xl text-red-900 leading-tight">
            {{ __('Stock Out Records') }}
        </h2>
    </div>
@endsection

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #fef2f2 0%, #f1f5f9 100%);
    }
    .remove-btn {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        font-weight: bold;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        transition: background 0.2s, transform 0.2s;
    }
    .remove-btn:hover {
        background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
        transform: scale(1.04);
    }
    .glass-table {
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1.5px solid #fecaca;
        box-shadow: 0 2px 12px 0 rgba(239, 68, 68, 0.1);
        background: #fff;
        width: 100%;
    }
    .glass-table th {
        position: sticky;
        top: 0;
        background: linear-gradient(90deg, #fee2e2 0%, #fef2f2 100%);
        z-index: 1;
        font-weight: 800;
        color: #991b1b;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.05);
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
        background: #fef2f2;
    }
    .glass-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .glass-table tr:hover {
        background: #fee2e2 !important;
        box-shadow: 0 4px 16px 0 rgba(220, 38, 38, 0.1);
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
<div class="max-w-6xl mx-auto mt-8">
    <div class="flex justify-end mb-4">
        <a href="{{ route('supplier.stockout.create') }}" class="remove-btn inline-flex items-center px-6 py-2 text-base rounded-xl shadow font-semibold uppercase tracking-wide">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
            Remove Stock
        </a>
    </div>
    <div class="glass-table overflow-hidden">
        <div class="p-6 text-gray-900">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Inventory Item</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Quantity Removed</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Removal Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOuts as $stockOut)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-red-900">{{ $stockOut->inventory->item_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">{{ $stockOut->quantity_removed }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">{{ $stockOut->removed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                                <h3>No stock out records found.</h3>
                                <p class="mt-1 text-sm text-gray-500">Click "Remove Stock" to record your first stock out entry.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

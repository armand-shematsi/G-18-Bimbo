@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Retail Inventory</h1>
    <a href="{{ route('retail.inventory.create') }}" class="btn btn-primary mb-3">Add New Inventory Item</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Reorder Level</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->unit_price }}</td>
                <td>{{ $item->reorder_level }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No inventory items found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.dashboard')

@section('header')
    Customer Segments
@endsection

@section('content')
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b">CustomerID</th>
                    <th class="px-4 py-2 border-b">Name</th>
                    <th class="px-4 py-2 border-b">Segment</th>
                    <th class="px-4 py-2 border-b">PurchaseFrequency</th>
                    <th class="px-4 py-2 border-b">AvgSpending</th>
                    <th class="px-4 py-2 border-b">Location</th>
                    <th class="px-4 py-2 border-b">PreferredBreadType</th>
                </tr>
            </thead>
            <tbody>
                @foreach($segments as $segment)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $segment->CustomerID }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->Name }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->Segment }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->PurchaseFrequency }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->AvgSpending }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->Location }}</td>
                        <td class="px-4 py-2 border-b">{{ $segment->PreferredBreadType }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $segments->links() }}
        </div>
    </div>
@endsection

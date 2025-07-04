@extends('layouts.retail-manager')

@section('header')
    Order #{{ $order->id }}
@endsection

@section('content')
<div class="py-10 min-h-screen bg-gradient-to-br from-gray-100 via-sky-100 to-indigo-100">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl border-4 border-indigo-400 p-10">
            <h2 class="text-3xl font-extrabold text-indigo-800 mb-8 flex items-center gap-3">
                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6" /></svg>
                Order Details
            </h2>
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-900 px-4 py-2 rounded-xl mb-4 border-l-4 border-emerald-500 font-semibold shadow">{{ session('success') }}</div>
            @endif
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:space-x-8">
                <div class="mb-2 md:mb-0">
                    <strong class="text-lg text-indigo-800">Status:</strong>
                    <span class="inline-flex items-center px-4 py-1 rounded-full font-bold text-base shadow
                        @if($order->status=='pending') bg-amber-100 text-amber-700 border border-amber-300
                        @elseif($order->status=='processing') bg-sky-100 text-sky-700 border border-sky-300
                        @elseif($order->status=='shipped') bg-teal-100 text-teal-700 border border-teal-300
                        @elseif($order->status=='delivered') bg-emerald-100 text-emerald-700 border border-emerald-300
                        @elseif($order->status=='cancelled') bg-rose-100 text-rose-700 border border-rose-300
                        @else bg-gray-200 text-gray-900 border border-gray-300 @endif">
                        <svg class="w-4 h-4 mr-1"
                            @if($order->status=='pending') fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            @elseif($order->status=='processing') fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            @elseif($order->status=='shipped') fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            @elseif($order->status=='delivered') fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            @elseif($order->status=='cancelled') fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            @else fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" @endif>
                            @if($order->status=='pending')<circle cx="12" cy="12" r="10" />
                            @elseif($order->status=='processing')<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            @elseif($order->status=='shipped')<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            @elseif($order->status=='delivered')<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                            @elseif($order->status=='cancelled')<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            @else <circle cx="12" cy="12" r="10" /> @endif
                        </svg>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <form method="POST" action="{{ route('retail.orders.changeStatus', $order->id) }}" class="flex items-center space-x-2">
                    @csrf
                    <select name="status" class="border-2 border-indigo-400 rounded-lg px-3 py-1 bg-indigo-50 text-indigo-900 font-semibold focus:ring-2 focus:ring-indigo-400">
                        <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                        <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                        <option value="shipped" @if($order->status=='shipped') selected @endif>Shipped</option>
                        <option value="delivered" @if($order->status=='delivered') selected @endif>Delivered</option>
                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-sky-400 text-white px-6 py-2 rounded-xl font-bold shadow hover:from-indigo-700 hover:to-sky-500 active:scale-95 transition-all">Update Status</button>
                </form>
            </div>
            <div class="mb-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <strong class="text-indigo-800">Customer:</strong> <span class="text-teal-700 font-semibold">{{ $order->user->name ?? 'N/A' }}</span><br>
                    <strong class="text-indigo-800">Placed At:</strong> <span class="text-sky-700">{{ $order->placed_at ? $order->placed_at->format('Y-m-d H:i') : '-' }}</span><br>
                </div>
                <div>
                    <strong class="text-indigo-800">Total:</strong> <span class="text-2xl font-bold text-indigo-800">${{ number_format($order->total, 2) }}</span><br>
                </div>
            </div>
            <div class="my-10">
                <h3 class="text-2xl font-bold text-orange-600 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Order Items
                </h3>
                <div class="overflow-x-auto rounded-2xl shadow-lg">
                    <table class="min-w-full divide-y divide-indigo-200">
                        <thead class="bg-gradient-to-r from-indigo-600 to-orange-400">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-extrabold text-white uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-extrabold text-white uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-extrabold text-white uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-extrabold text-white uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-indigo-100">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-sky-50 hover:scale-[1.01] hover:shadow-md transition-all duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-indigo-900 font-semibold">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-orange-700 font-bold">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sky-700 font-semibold">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-indigo-900 font-bold">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="my-10">
                <h3 class="text-2xl font-bold text-teal-700 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" /></svg>
                    Payment
                </h3>
                @if($order->payment)
                    <div class="bg-teal-50 border-l-4 border-teal-400 p-4 rounded-2xl shadow mb-2">
                        <strong>Status:</strong> <span class="text-teal-800 font-semibold">{{ ucfirst($order->payment->status) }}</span><br>
                        <strong>Method:</strong> <span class="text-teal-700">{{ $order->payment->payment_method }}</span><br>
                        <strong>Transaction ID:</strong> <span class="text-teal-700">{{ $order->payment->transaction_id }}</span><br>
                        <strong>Paid At:</strong> <span class="text-teal-700">{{ $order->payment->paid_at }}</span><br>
                    </div>
                @else
                    <div class="bg-amber-100 text-amber-800 px-4 py-2 rounded-xl font-semibold shadow">No payment recorded.</div>
                @endif
            </div>
            <div class="mt-10 flex flex-col md:flex-row gap-6">
                <a href="{{ route('retail.orders.edit', $order->id) }}" class="flex-1 text-center bg-gradient-to-r from-orange-500 to-orange-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:from-orange-600 hover:to-orange-800 active:scale-95 transition-all text-lg">Edit</a>
                <form method="POST" action="{{ route('retail.orders.destroy', $order->id) }}" onsubmit="return confirm('Are you sure?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-gradient-to-r from-rose-500 to-rose-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:from-rose-600 hover:to-rose-800 active:scale-95 transition-all text-lg">Cancel Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
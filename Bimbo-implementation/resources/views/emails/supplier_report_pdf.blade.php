<!DOCTYPE html>
<html>
<head>
    <title>Supplier Daily Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { color: #2d3748; }
        h3 { color: #4a5568; margin-top: 20px; }
        ul { margin: 0 0 20px 20px; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Supplier Daily Report for {{ $supplier->name }}</h2>
    <h3>Items Delivered Today</h3>
    @if(count($deliveredItems))
        <ul>
        @foreach($deliveredItems as $order)
            @foreach($order->items as $item)
                <li>{{ $item->product_name }} x {{ $item->quantity }}</li>
            @endforeach
        @endforeach
        </ul>
    @else
        <p>No items delivered today.</p>
    @endif

    <h3>Pending Payments</h3>
    @if(count($pendingPayments))
        <ul>
        @foreach($pendingPayments as $payment)
            <li>Order #{{ $payment->order_id }}: ${{ number_format($payment->amount, 2) }}</li>
        @endforeach
        </ul>
    @else
        <p>No pending payments.</p>
    @endif

    <h3>Stock Needs</h3>
    @if(count($stockNeeds))
        <ul>
        @foreach($stockNeeds as $stock)
            <li>{{ $stock->item_name }} ({{ $stock->status }}): {{ $stock->quantity }} {{ $stock->unit }}</li>
        @endforeach
        </ul>
    @else
        <p>No stock needs.</p>
    @endif
</body>
</html>

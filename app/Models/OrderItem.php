<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

// Example for recent orders in your controller
$recentOrders = \App\Models\Order::with('items.product')->latest()->take(5)->get();
return view('dashboard.retail-manager', compact('recentOrders'));
?>
@foreach($recentOrders as $order)
    <div class="order">
        <strong>Order #{{ $order->id }}</strong>
        <ul>
            @foreach($order->items as $item)
                <li>
                    {{ $item->product->name }} &times; {{ $item->quantity }}
                </li>
            @endforeach
        </ul>
    </div>
@endforeach

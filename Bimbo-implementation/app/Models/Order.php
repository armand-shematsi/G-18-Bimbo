<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'vendor_id', 'customer_name', 'customer_email', 'status', 'total', 'payment_status', 'shipping_address', 'billing_address', 'placed_at', 'delivered_at', 'notes', 'fulfillment_type', 'tracking_number', 'delivery_option'
    ];

    protected $casts = [
        'placed_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class, 'vendor_id');
    }

    // Example status transition method
    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }
}

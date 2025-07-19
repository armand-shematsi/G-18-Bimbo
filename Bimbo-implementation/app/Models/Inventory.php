<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name',
        'quantity',
        'unit_price',
        'unit',
        'item_type',
        'reorder_level',
        'location',
        'user_id', // <-- Added for supplier assignment
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inventory) {
            $inventory->updateStatus();
        });
    }

    public function needsReorder()
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function updateStatus()
    {
        if ($this->quantity == 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->quantity <= $this->reorder_level) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'available';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Get the product that owns the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}


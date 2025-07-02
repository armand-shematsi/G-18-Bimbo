<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name', 'item_type', 'quantity', 'unit', 'status', 'reorder_level', 'user_id', 'unit_price'
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
}


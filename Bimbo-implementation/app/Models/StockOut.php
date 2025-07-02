<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inventory_id',
        'quantity_removed',
        'removed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    // Optional: relate to StockIn if you want to track which stock-in this stock-out is from
    // public function stockIn()
    // {
    //     return $this->belongsTo(StockIn::class, 'inventory_id', 'inventory_id');
    // }
}

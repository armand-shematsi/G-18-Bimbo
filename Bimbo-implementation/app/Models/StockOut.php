<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'inventory_id',
        'quantity_removed',
        'removed_at',
    ];

    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'supplier_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
}
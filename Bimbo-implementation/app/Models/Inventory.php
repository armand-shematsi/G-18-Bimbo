<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model

{
    protected $fillable = [
        'vendor_id', 'item_name', 'item_type', 'quantity', 'unit', 'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model

{
    protected $fillable = [
        'item_name', 'item_type', 'quantity', 'unit'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model

{
    protected $fillable = [
        'item_name', 'item_type', 'quantity', 'unit', 'status', 'reorder_level', 'user_id'
    ];

    public function needsReorder()
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


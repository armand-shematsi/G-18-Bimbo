<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'product',
        'quantity',
        'status',
    ];

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }
} 
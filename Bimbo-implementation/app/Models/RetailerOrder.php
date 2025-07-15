<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    protected $fillable = ['retailer_id', 'product_id', 'quantity', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function items()
    {
        return $this->hasMany(RetailerOrderItem::class);
    }
}

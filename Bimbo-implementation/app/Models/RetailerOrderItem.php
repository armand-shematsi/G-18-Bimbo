<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerOrderItem extends Model
{
    protected $fillable = ['retailer_order_id', 'product_id', 'item_name', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function retailerOrder()
    {
        return $this->belongsTo(RetailerOrder::class);
    }
}

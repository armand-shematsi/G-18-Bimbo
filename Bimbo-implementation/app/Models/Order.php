<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'customer_name',
        'customer_email',
        'status',
        'total',
        // add other fields as needed
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

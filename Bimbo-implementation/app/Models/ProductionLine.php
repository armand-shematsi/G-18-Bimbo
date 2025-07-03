<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionLine extends Model
{
    protected $fillable = [
        'name',
        'status',
        'current_product',
        'output',
        'efficiency'
    ];
}

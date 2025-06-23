<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'stock_quantity',
        'low_stock_threshold',
    ];

    // If you want to track usage per batch, you can uncomment:
    // public function productionBatches()
    // {
    //     return $this->belongsToMany(ProductionBatch::class, 'batch_ingredient')->withPivot('quantity_used');
    // }
}

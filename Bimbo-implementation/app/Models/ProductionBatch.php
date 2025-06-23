<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $fillable = [
        'name',
        'status',
        'scheduled_start',
        'actual_start',
        'actual_end',
        'notes',
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'batch_ingredient')->withPivot('quantity_used');
    }
}

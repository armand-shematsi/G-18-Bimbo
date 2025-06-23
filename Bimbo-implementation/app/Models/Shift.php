<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'production_batch_id',
        'start_time',
        'end_time',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'production_batch_id',
        'start_time',
        'end_time',
        'role',
        'supply_center_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function supplyCenter()
    {
        return $this->belongsTo(SupplyCenter::class);
    }
}

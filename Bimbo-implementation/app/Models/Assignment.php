<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['staff_id', 'supply_center_id', 'shift_time', 'status'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function supplyCenter()
    {
        return $this->belongsTo(SupplyCenter::class);
    }
}

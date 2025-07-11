<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSupplyCenterAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'supply_center_id', 'shift_id', 'status', 'assigned_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function supplyCenter()
    {
        return $this->belongsTo(SupplyCenter::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name',
        'type',
        'status',
        'last_maintenance_at',
        'notes',
    ];

    public function maintenanceTasks()
    {
        return $this->hasMany(MaintenanceTask::class);
    }
}

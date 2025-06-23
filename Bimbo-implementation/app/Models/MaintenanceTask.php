<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    protected $fillable = [
        'machine_id',
        'scheduled_for',
        'completed_at',
        'description',
        'status',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}

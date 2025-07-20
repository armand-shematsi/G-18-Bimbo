<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'business_type',
        'tax_id',
        'business_license',
        'status',
        'sales',
        'annual_revenue',
        'years_in_business',
        'regulatory_certification',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'sales' => 'float',
        'annual_revenue' => 'float',
        'years_in_business' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

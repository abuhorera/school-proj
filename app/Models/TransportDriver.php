<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportDriver extends Model
{
    use HasFactory;

    protected $table = 'transport_drivers';

    protected $fillable = [
        'name',
        'driver_id_code',
        'phone',
        'license_number',
        'license_expiry',
        'address',
        'status', // Active, Suspended, Left
        'device_info',
        'last_signed_in_at',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'last_signed_in_at' => 'datetime',
    ];

    public function vehicles()
    {
        return $this->hasMany(TransportVehicle::class, 'driver_id');
    }
}


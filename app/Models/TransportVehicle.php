<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    use HasFactory;

    protected $table = 'transport_vehicles';

    protected $fillable = [
        'vehicle_no',
        'vehicle_model',
        'seating_capacity',
        'driver_name',
        'driver_phone',
        'driver_license',
        'status', // Active, In Service, On a trip, Maintenance
        'driver_id',
        'driver_password',
        'gps_device_imei',
        'api_token',
        'tracking_enabled',
        'note',
        'current_latitude',
        'current_longitude',
        'speed',
        'last_ping',
    ];

    protected $casts = [
        'tracking_enabled' => 'boolean',
        'current_latitude' => 'decimal:6',
        'current_longitude' => 'decimal:6',
        'last_ping' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(TransportDriver::class, 'driver_id');
    }

    public function route()
    {
        return $this->hasOne(TransportRoute::class, 'vehicle_id');
    }

    public function boardingLogs()
    {
        return $this->hasMany(TransportBoardingLog::class, 'transport_vehicle_id');
    }
}


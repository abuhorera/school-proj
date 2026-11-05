<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricDeviceCommand extends Model
{
    use HasFactory;

    protected $table = 'biometric_device_commands';

    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }
}


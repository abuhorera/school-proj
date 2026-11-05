<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricSystemLog extends Model
{
    use HasFactory;

    protected $table = 'biometric_system_logs';

    protected $guarded = [];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }
}


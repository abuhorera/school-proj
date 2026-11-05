<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricMonitoringLog extends Model
{
    use HasFactory;

    protected $table = 'biometric_monitoring_logs';

    protected $guarded = [];

    protected $casts = [
        'detected_at' => 'datetime',
        'confidence' => 'decimal:2',
    ];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'person_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'person_id');
    }
}


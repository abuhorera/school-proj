<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricDevice extends Model
{
    use HasFactory;

    protected $table = 'biometric_devices';

    protected $guarded = [];

    protected $casts = [
        'is_attendance' => 'boolean',
        'is_monitoring' => 'boolean',
        'last_sync_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
        'device_info' => 'array',
    ];

    public function logs()
    {
        return $this->hasMany(BiometricLog::class, 'device_id');
    }

    public function monitoringLogs()
    {
        return $this->hasMany(BiometricMonitoringLog::class, 'device_id');
    }

    public function commands()
    {
        return $this->hasMany(BiometricDeviceCommand::class, 'device_id');
    }

    public function getModeBadgeAttribute()
    {
        return match ($this->push_mode) {
            'agent' => ['label' => 'Agent Sync', 'color' => 'primary', 'desc' => 'LAN devices & full remote control'],
            'cloud_push' => ['label' => 'Cloud Push', 'color' => 'success', 'desc' => 'ADMS (iclock) & JSON webhook — no software'],
            'camera' => ['label' => 'IP Camera AI', 'color' => 'purple', 'desc' => 'RTSP feeds & AI face recognition'],
            default => ['label' => 'Agent Sync', 'color' => 'primary', 'desc' => 'Local ZK agent']
        };
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'inactive') {
            return ['label' => 'Disabled', 'color' => 'secondary'];
        }
        if ($this->online_status === 'online') {
            return ['label' => 'Online', 'color' => 'success'];
        }
        if ($this->online_status === 'agent_silent') {
            return ['label' => 'Agent silent', 'color' => 'warning'];
        }
        return ['label' => 'Offline', 'color' => 'danger'];
    }
}


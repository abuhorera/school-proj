<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceSchedule extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenance_schedules';

    protected $fillable = [
        'asset_id',
        'title',
        'maintenance_type',
        'frequency',
        'interval_days',
        'next_due_date',
        'assigned_to',
        'vendor_id',
        'vendor_name',
        'estimated_cost',
        'reminder_days_before',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'interval_days' => 'integer',
        'reminder_days_before' => 'integer',
        'estimated_cost' => 'float',
        'is_active' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }

    public function logs()
    {
        return $this->hasMany(AssetMaintenanceLog::class, 'schedule_id');
    }
}


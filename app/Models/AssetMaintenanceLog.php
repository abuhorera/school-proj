<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'asset_maintenance_logs';

    protected $fillable = [
        'asset_id',
        'schedule_id',
        'maintenance_type',
        'status',
        'problem_description',
        'reported_date',
        'completed_date',
        'cost',
        'performed_by',
        'vendor_name',
        'action_taken',
        'external_provider',
        'document_path',
        'remarks',
    ];

    protected $casts = [
        'reported_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'float',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }

    public function schedule()
    {
        return $this->belongsTo(AssetMaintenanceSchedule::class, 'schedule_id');
    }
}


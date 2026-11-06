<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAudit extends Model
{
    use HasFactory;

    protected $table = 'asset_audits';

    protected $fillable = [
        'title',
        'scope',
        'total_assets',
        'verified_count',
        'present_count',
        'missing_count',
        'damaged_count',
        'moved_count',
        'status',
        'conducted_by',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_assets' => 'integer',
        'verified_count' => 'integer',
        'present_count' => 'integer',
        'missing_count' => 'integer',
        'damaged_count' => 'integer',
        'moved_count' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(AssetAuditItem::class, 'asset_audit_id');
    }
}


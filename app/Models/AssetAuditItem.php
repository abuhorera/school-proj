<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAuditItem extends Model
{
    use HasFactory;

    protected $table = 'asset_audit_items';

    protected $fillable = [
        'asset_audit_id',
        'asset_id',
        'expected_location',
        'expected_holder',
        'result',
        'found_location',
        'remarks',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function audit()
    {
        return $this->belongsTo(AssetAudit::class, 'asset_audit_id');
    }

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }
}


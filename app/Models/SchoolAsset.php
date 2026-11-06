<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAsset extends Model
{
    use HasFactory;

    protected $table = 'school_assets';

    protected $fillable = [
        'name',
        'asset_tag',
        'asset_category_id',
        'brand',
        'model_number',
        'serial_number',
        'quantity',
        'description',
        'status',
        'condition',
        'location',
        'supplier_id',
        'supplier_name',
        'funding_source',
        'purchase_date',
        'purchase_cost',
        'invoice_number',
        'depreciation_method',
        'depreciation_start_date',
        'useful_life_months',
        'salvage_value',
        'depreciation_rate',
        'opening_book_value',
        'current_book_value',
        'warranty_provider',
        'warranty_expiry_date',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_expiry_date',
        'image_path',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'depreciation_start_date' => 'date',
        'warranty_expiry_date' => 'date',
        'insurance_expiry_date' => 'date',
        'purchase_cost' => 'float',
        'salvage_value' => 'float',
        'depreciation_rate' => 'float',
        'opening_book_value' => 'float',
        'current_book_value' => 'float',
        'quantity' => 'integer',
        'useful_life_months' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class, 'asset_id');
    }

    public function activeAssignment()
    {
        return $this->hasOne(AssetAssignment::class, 'asset_id')->where('status', 'active')->latestOfMany();
    }

    public function depreciations()
    {
        return $this->hasMany(AssetDepreciation::class, 'asset_id');
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(AssetMaintenanceSchedule::class, 'asset_id');
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(AssetMaintenanceLog::class, 'asset_id');
    }

    public function disposals()
    {
        return $this->hasMany(AssetDisposal::class, 'asset_id');
    }

    public function auditItems()
    {
        return $this->hasMany(AssetAuditItem::class, 'asset_id');
    }

    public function movements()
    {
        return $this->hasMany(AssetMovement::class, 'asset_id')->orderBy('event_date', 'desc');
    }

    // Helper for status badge
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'in_use' => 'badge-info',
            'in_store' => 'badge-secondary',
            'under_maintenance' => 'badge-warning',
            'retired' => 'badge-dark',
            'disposed' => 'badge-danger',
            'lost' => 'badge-danger',
            default => 'badge-light'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'in_use' => 'In Use',
            'in_store' => 'In Store',
            'under_maintenance' => 'Under Maintenance',
            'retired' => 'Retired',
            'disposed' => 'Disposed',
            'lost' => 'Lost',
            default => ucfirst(str_replace('_', ' ', $this->status))
        };
    }
}


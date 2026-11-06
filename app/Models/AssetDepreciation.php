<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    use HasFactory;

    protected $table = 'asset_depreciations';

    protected $fillable = [
        'asset_id',
        'period_name',
        'fiscal_year',
        'depreciation_date',
        'method',
        'opening_value',
        'depreciation_amount',
        'accumulated_depreciation',
        'closing_value',
        'posted_by',
    ];

    protected $casts = [
        'depreciation_date' => 'date',
        'opening_value' => 'float',
        'depreciation_amount' => 'float',
        'accumulated_depreciation' => 'float',
        'closing_value' => 'float',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }
}


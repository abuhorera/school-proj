<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;

    protected $table = 'asset_categories';

    protected $fillable = [
        'name',
        'code',
        'default_depreciation_method',
        'default_useful_life_months',
        'default_salvage_value_pct',
        'default_depreciation_rate',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'default_useful_life_months' => 'integer',
        'default_salvage_value_pct' => 'float',
        'default_depreciation_rate' => 'float',
    ];

    public function assets()
    {
        return $this->hasMany(SchoolAsset::class, 'asset_category_id');
    }
}


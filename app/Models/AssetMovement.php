<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMovement extends Model
{
    use HasFactory;

    protected $table = 'asset_movements';

    protected $fillable = [
        'asset_id',
        'event_type',
        'event_date',
        'details',
        'actor',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(SchoolAsset::class, 'asset_id');
    }
}


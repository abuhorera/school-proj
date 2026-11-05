<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HpcCardSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'show_radar_chart' => 'boolean',
        'show_monthly_attendance' => 'boolean',
        'show_health_metrics' => 'boolean',
    ];
}


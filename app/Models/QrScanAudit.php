<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScanAudit extends Model
{
    use HasFactory;

    protected $table = 'qr_scan_audits';

    protected $guarded = [];

    protected $casts = [
        'scan_time' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
}


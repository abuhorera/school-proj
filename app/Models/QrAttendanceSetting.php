<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrAttendanceSetting extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_settings';

    protected $guarded = [];

    protected $casts = [
        'auto_attendance' => 'boolean',
        'machine_types' => 'array',
        'allowed_formats' => 'array',
    ];
}


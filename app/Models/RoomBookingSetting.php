<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBookingSetting extends Model
{
    use HasFactory;

    protected $table = 'room_booking_settings';
    protected $guarded = [];

    protected $casts = [
        'teachers_can_request' => 'boolean',
        'requests_need_approval' => 'boolean',
    ];
}


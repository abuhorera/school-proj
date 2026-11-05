<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMaintenance extends Model
{
    use HasFactory;

    protected $table = 'room_maintenances';
    protected $guarded = [];

    protected $casts = [
        'is_unusable' => 'boolean',
        'reported_at' => 'date',
        'resolved_at' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(ClassroomRoom::class, 'room_id');
    }
}


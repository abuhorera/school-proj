<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomLabelMatch extends Model
{
    use HasFactory;

    protected $table = 'room_label_matches';
    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(ClassroomRoom::class, 'room_id');
    }
}


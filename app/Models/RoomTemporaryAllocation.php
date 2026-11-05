<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTemporaryAllocation extends Model
{
    use HasFactory;

    protected $table = 'room_temporary_allocations';
    protected $guarded = [];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(ClassroomRoom::class, 'room_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}


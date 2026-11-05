<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomRoom extends Model
{
    use HasFactory;

    protected $table = 'classroom_rooms';
    protected $guarded = [];

    protected $casts = [
        'facilities_json' => 'array',
        'is_exam_hall' => 'boolean',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function homeSection()
    {
        return $this->belongsTo(Section::class, 'home_section_id');
    }

    public function bookings()
    {
        return $this->hasMany(RoomBooking::class, 'room_id');
    }

    public function maintenances()
    {
        return $this->hasMany(RoomMaintenance::class, 'room_id');
    }

    public function temporaryAllocations()
    {
        return $this->hasMany(RoomTemporaryAllocation::class, 'room_id');
    }

    public function activeMaintenance()
    {
        return $this->hasOne(RoomMaintenance::class, 'room_id')->whereIn('status', ['Reported', 'Being fixed'])->latestOfMany();
    }
}


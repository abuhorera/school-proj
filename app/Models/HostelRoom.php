<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    public function roomType()
    {
        return $this->belongsTo(HostelRoomType::class, 'room_type_id');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'hostel_room_id');
    }

    public function occupiedCount()
    {
        return $this->allocations()->where('status', 'Active')->count();
    }

    public function isFull()
    {
        return $this->occupiedCount() >= $this->capacity;
    }

    public function availableBeds()
    {
        return max(0, $this->capacity - $this->occupiedCount());
    }

    public function statusLabel()
    {
        $occ = $this->occupiedCount();
        if ($occ == 0) {
            return 'Available';
        } elseif ($occ >= $this->capacity) {
            return 'Full';
        } else {
            return 'Partially Available';
        }
    }

    public function statusClass()
    {
        $occ = $this->occupiedCount();
        if ($occ == 0) {
            return 'success';
        } elseif ($occ >= $this->capacity) {
            return 'danger';
        } else {
            return 'warning';
        }
    }
}


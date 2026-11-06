<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class, 'hostel_id');
    }

    public function allocations()
    {
        return $this->hasManyThrough(HostelAllocation::class, HostelRoom::class, 'hostel_id', 'hostel_room_id');
    }

    public function totalCapacity()
    {
        return $this->rooms->sum('capacity');
    }

    public function occupiedBeds()
    {
        return $this->allocations()->where('hostel_allocations.status', 'Active')->count();
    }

    public function availableBeds()
    {
        return max(0, $this->totalCapacity() - $this->occupiedBeds());
    }

    public function occupancyPercent()
    {
        $total = $this->totalCapacity();
        if ($total <= 0) return 0;
        return round(($this->occupiedBeds() / $total) * 100);
    }
}


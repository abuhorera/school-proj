<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceCalendarEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function getTypeBadgeAttribute()
    {
        return match (strtolower($this->event_type)) {
            'statutory' => 'badge-primary',
            'document expiry' => 'badge-danger',
            'reporting window' => 'badge-warning',
            default => 'badge-info',
        };
    }

    public function getDaysRemainingAttribute()
    {
        $days = (int) now()->startOfDay()->diffInDays($this->event_date->startOfDay(), false);
        return $days >= 0 ? "{$days}d" : abs($days) . "d ago";
    }
}


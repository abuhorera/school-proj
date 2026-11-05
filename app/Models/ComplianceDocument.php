<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'owner_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'owner_id');
    }

    public function getComputedStatusAttribute()
    {
        if (!$this->expiry_date) {
            return 'Valid';
        }

        $now = now();
        if ($this->expiry_date->isPast()) {
            return 'Expired';
        }

        if ($this->expiry_date->diffInDays($now) <= 60) {
            return 'Expiring';
        }

        return 'Valid';
    }

    public function getStatusBadgeAttribute()
    {
        return match (strtolower($this->computed_status)) {
            'valid' => 'badge-success',
            'expiring' => 'badge-warning',
            'expired' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}


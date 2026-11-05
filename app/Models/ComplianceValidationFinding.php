<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceValidationFinding extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'waived_until' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'owner_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'owner_id');
    }

    public function getSeverityBadgeAttribute()
    {
        return match (strtolower($this->severity)) {
            'critical' => 'badge-danger',
            'error' => 'badge-danger',
            'warning' => 'badge-warning',
            default => 'badge-info',
        };
    }
}


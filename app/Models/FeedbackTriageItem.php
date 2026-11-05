<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackTriageItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'flagged_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    public function getPriorityBadgeAttribute()
    {
        return match (strtolower($this->priority)) {
            'urgent' => 'badge-danger',
            'high' => 'badge-warning',
            'normal' => 'badge-info',
            'low' => 'badge-secondary',
            default => 'badge-info',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match (strtolower($this->status)) {
            'new' => 'badge-primary',
            'reviewed' => 'badge-info',
            'in progress' => 'badge-warning',
            'actioned' => 'badge-success',
            'dismissed' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}


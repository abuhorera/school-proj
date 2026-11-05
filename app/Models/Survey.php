<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'allow_resubmit' => 'boolean',
        'force_popup' => 'boolean',
        'one_per_device' => 'boolean',
        'is_report_published' => 'boolean',
        'closes_at' => 'datetime',
        'report_published_at' => 'datetime',
        'target_role_ids' => 'array',
        'target_class_ids' => 'array',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order_num');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function feedbackItems()
    {
        return $this->hasMany(FeedbackTriageItem::class);
    }

    public function getResponseRateAttribute()
    {
        if ($this->invited_count <= 0) {
            return $this->responses_count > 0 ? 100 : 0;
        }
        return round(($this->responses_count / $this->invited_count) * 100);
    }

    public function getStatusBadgeAttribute()
    {
        return match (strtolower($this->status)) {
            'published' => 'badge-success',
            'draft' => 'badge-secondary',
            'closed' => 'badge-dark',
            'scheduled' => 'badge-info',
            'archived' => 'badge-light',
            default => 'badge-secondary',
        };
    }

    public function getKindLabelAttribute()
    {
        return match (strtolower($this->kind)) {
            'poll' => 'Poll',
            'feedback_form' => 'Feedback form',
            default => 'Survey',
        };
    }
}


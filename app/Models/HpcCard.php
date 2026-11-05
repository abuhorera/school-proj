<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HpcCard extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'general_info_json' => 'array',
        'attendance_json' => 'array',
        'self_assessment_json' => 'array',
        'peer_assessment_json' => 'array',
        'parent_feedback_json' => 'array',
        'teacher_summary_json' => 'array',
        'domain_scores_json' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function framework()
    {
        return $this->belongsTo(HpcFramework::class, 'framework_id');
    }

    public function getPartsFilledCountAttribute(): int
    {
        $count = 0;
        if (!empty($this->general_info_json)) $count++;
        if (!empty($this->self_assessment_json) || !empty($this->peer_assessment_json) || !empty($this->parent_feedback_json)) $count++;
        if (!empty($this->domain_scores_json) || !empty($this->teacher_summary_json)) $count++;
        return $count;
    }

    public function getTotalPartsCountAttribute(): int
    {
        return 3;
    }
}


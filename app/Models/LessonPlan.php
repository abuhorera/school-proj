<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;

    protected $table = 'lesson_plans';

    protected $fillable = [
        'school_id',
        'title',
        'class_id',
        'section_id',
        'subject_id',
        'staff_id',
        'teacher_id',
        'week_number',
        'week_start_date',
        'week_end_date',
        'planned_periods',
        'chapter_names',
        'learning_objectives',
        'topic',
        'subtopics',
        'adhoc_topics',
        'teaching_method',
        'teaching_methods',
        'teaching_aids',
        'resources_required',
        'homework_planned',
        'status',
        'coverage_percentage',
        'review_feedback',
        'reviewed_by',
        'approved_by',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'teaching_methods' => 'array',
        'teaching_aids' => 'array',
        'coverage_percentage' => 'float',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->withDefault(function ($staff, $plan) {
            if ($plan->teacher_id) {
                return Staff::find($plan->teacher_id);
            }
            return null;
        });
    }

    public function topics()
    {
        return $this->hasMany(LessonPlanTopic::class, 'lesson_plan_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Staff::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(Staff::class, 'approved_by');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlanTopic extends Model
{
    use HasFactory;

    protected $table = 'lesson_plan_topics';

    protected $fillable = [
        'school_id',
        'lesson_plan_id',
        'topic_name',
        'subtopic_name',
        'periods_allocated',
        'coverage_status',
        'coverage_reason',
        'covered_on',
    ];

    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlan::class, 'lesson_plan_id');
    }
}


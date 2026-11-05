<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlannerSetting extends Model
{
    use HasFactory;

    protected $table = 'lesson_planner_settings';

    protected $fillable = [
        'school_id',
        'hod_step_enabled',
        'allow_edit_after_submit',
        'reminder_enabled',
        'reminder_day',
        'reminder_time',
        'submission_deadline_day',
        'submission_deadline_time',
    ];

    protected $casts = [
        'hod_step_enabled' => 'boolean',
        'allow_edit_after_submit' => 'boolean',
        'reminder_enabled' => 'boolean',
    ];
}


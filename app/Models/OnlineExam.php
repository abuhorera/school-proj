<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineExam extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration' => 'integer',
        'total_marks' => 'decimal:2',
        'passing_percentage' => 'decimal:2',
        'negative_marking' => 'boolean',
        'negative_mark_rate' => 'decimal:2',
        'auto_publish_results' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function examQuestions()
    {
        return $this->hasMany(OnlineExamQuestion::class, 'online_exam_id')->orderBy('sort_order');
    }

    public function questions()
    {
        return $this->belongsToMany(OnlineQuestion::class, 'online_exam_questions', 'online_exam_id', 'online_question_id')
                    ->withPivot('marks', 'sort_order')
                    ->withTimestamps();
    }

    public function attempts()
    {
        return $this->hasMany(OnlineExamAttempt::class, 'online_exam_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'marks' => 'decimal:2',
        'negative_marks' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function topic()
    {
        return $this->belongsTo(QuestionTopic::class, 'question_topic_id');
    }

    public function examQuestions()
    {
        return $this->hasMany(OnlineExamQuestion::class, 'online_question_id');
    }

    public function exams()
    {
        return $this->belongsToMany(OnlineExam::class, 'online_exam_questions', 'online_question_id', 'online_exam_id');
    }
}


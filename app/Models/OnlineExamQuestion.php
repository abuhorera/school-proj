<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineExamQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'marks' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(OnlineExam::class, 'online_exam_id');
    }

    public function question()
    {
        return $this->belongsTo(OnlineQuestion::class, 'online_question_id');
    }
}


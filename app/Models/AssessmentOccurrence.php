<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentOccurrence extends Model
{
    use HasFactory;

    protected $table = 'assessment_occurrences';

    protected $guarded = [];

    protected $casts = [
        'sitting_date' => 'date',
        'published_at' => 'datetime',
    ];

    public function assessment()
    {
        return $this->belongsTo(ClassroomAssessment::class, 'assessment_id');
    }

    public function marks()
    {
        return $this->hasMany(AssessmentStudentMark::class, 'occurrence_id');
    }

    public function getAverageScoreAttribute()
    {
        $validMarks = $this->marks()->where('is_absent', false)->whereNotNull('marks_obtained')->pluck('marks_obtained');
        if ($validMarks->isEmpty()) {
            return 0;
        }
        return round($validMarks->average(), 1);
    }

    public function getAveragePercentageAttribute()
    {
        $avg = $this->average_score;
        $total = $this->assessment?->total_marks ?: 30;
        if ($total <= 0) return 0;
        return round(($avg / $total) * 100, 1);
    }
}


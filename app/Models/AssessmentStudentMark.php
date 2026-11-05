<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStudentMark extends Model
{
    use HasFactory;

    protected $table = 'assessment_student_marks';

    protected $guarded = [];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_absent' => 'boolean',
    ];

    public function occurrence()
    {
        return $this->belongsTo(AssessmentOccurrence::class, 'occurrence_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function getPercentageAttribute()
    {
        if ($this->is_absent || $this->marks_obtained === null) {
            return null;
        }
        $total = $this->occurrence?->assessment?->total_marks ?: 100;
        return round(($this->marks_obtained / $total) * 100, 1);
    }

    public function getGradeBadgeAttribute()
    {
        if ($this->is_absent) {
            return ['grade' => 'AB', 'color' => 'danger'];
        }
        $pct = $this->percentage;
        if ($pct === null) return ['grade' => '-', 'color' => 'secondary'];
        if ($pct >= 90) return ['grade' => 'A+', 'color' => 'success'];
        if ($pct >= 80) return ['grade' => 'A', 'color' => 'success'];
        if ($pct >= 70) return ['grade' => 'B', 'color' => 'primary'];
        if ($pct >= 60) return ['grade' => 'C', 'color' => 'info'];
        if ($pct >= 40) return ['grade' => 'D', 'color' => 'warning'];
        return ['grade' => 'E (Fail)', 'color' => 'danger'];
    }
}


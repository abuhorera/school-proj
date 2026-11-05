<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomAssessment extends Model
{
    use HasFactory;

    protected $table = 'classroom_assessments';

    protected $guarded = [];

    protected $casts = [
        'scheduled_date' => 'date',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
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

    public function occurrences()
    {
        return $this->hasMany(AssessmentOccurrence::class, 'assessment_id');
    }

    public function getClassSectionTextAttribute()
    {
        $cls = $this->schoolClass?->name ?? 'Class';
        if ($this->section) {
            return $cls . ' / ' . $this->section->name;
        }
        return $cls . ' (Whole Class)';
    }

    public function getTypeBadgeColorAttribute()
    {
        return match ($this->type) {
            'Quiz' => 'info',
            'Test' => 'primary',
            'Oral' => 'warning',
            'Practical' => 'purple',
            'Assignment' => 'success',
            default => 'secondary'
        };
    }
}


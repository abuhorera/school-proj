<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyClasswork extends Model
{
    use HasFactory;

    protected $table = 'study_classworks';

    protected $fillable = [
        'school_id',
        'school_class_id',
        'section_id',
        'subject_id',
        'staff_id',
        'date',
        'type',
        'topic',
        'content',
        'file_path',
    ];

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

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}


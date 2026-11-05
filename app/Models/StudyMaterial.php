<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $table = 'study_materials';

    protected $fillable = [
        'school_id',
        'school_class_id',
        'subject_id',
        'syllabus_id',
        'title',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'content_link',
        'staff_id',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(StudySyllabus::class, 'syllabus_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}


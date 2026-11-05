<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyHomework extends Model
{
    use HasFactory;

    protected $table = 'study_homeworks';

    protected $fillable = [
        'school_id',
        'school_class_id',
        'section_id',
        'subject_id',
        'staff_id',
        'title',
        'description',
        'due_date',
        'study_material_id',
        'file_path',
        'status',
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

    public function material()
    {
        return $this->belongsTo(StudyMaterial::class, 'study_material_id');
    }

    public function submissions()
    {
        return $this->hasMany(StudyHomeworkSubmission::class, 'homework_id');
    }
}


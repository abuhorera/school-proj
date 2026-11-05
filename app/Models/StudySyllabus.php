<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySyllabus extends Model
{
    use HasFactory;

    protected $table = 'study_syllabuses';

    protected $fillable = [
        'school_id',
        'school_class_id',
        'subject_id',
        'parent_id',
        'title',
        'description',
        'order_no',
        'status',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function parent()
    {
        return $this->belongsTo(StudySyllabus::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(StudySyllabus::class, 'parent_id')->orderBy('order_no');
    }

    public function materials()
    {
        return $this->hasMany(StudyMaterial::class, 'syllabus_id');
    }
}


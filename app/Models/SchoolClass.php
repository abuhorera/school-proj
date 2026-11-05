<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $guarded = [];

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Staff::class, 'coordinator_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, StudentEnrollment::class, 'class_id', 'id', 'id', 'student_id');
    }
}

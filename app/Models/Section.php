<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function inChargeTeacher()
    {
        return $this->belongsTo(Staff::class, 'in_charge_teacher_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'section_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'section_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'section_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, StudentEnrollment::class, 'section_id', 'id', 'id', 'student_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'session_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, StudentEnrollment::class, 'session_id', 'id', 'id', 'student_id');
    }
}


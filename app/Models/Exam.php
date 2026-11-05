<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'class_ids' => 'array',
        'is_published' => 'boolean',
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function uploadedMarksheets()
    {
        return $this->hasMany(UploadedMarksheet::class);
    }

    public function remarks()
    {
        return $this->hasMany(StudentExamRemark::class);
    }

    public function cocurricularGrades()
    {
        return $this->hasMany(CocurricularGrade::class);
    }
}

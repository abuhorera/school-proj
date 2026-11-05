<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyHomeworkSubmission extends Model
{
    use HasFactory;

    protected $table = 'study_homework_submissions';

    protected $fillable = [
        'homework_id',
        'student_id',
        'submitted_at',
        'file_path',
        'file_name',
        'marks',
        'remarks',
        'status',
    ];

    public function homework()
    {
        return $this->belongsTo(StudyHomework::class, 'homework_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}


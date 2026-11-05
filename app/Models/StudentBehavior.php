<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentBehavior extends Model
{
    use HasFactory;

    protected $table = 'student_behavior_records';
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}


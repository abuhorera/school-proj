<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HpcActivityMark extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'levels_json' => 'array',
    ];

    public function activity()
    {
        return $this->belongsTo(HpcActivity::class, 'activity_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}


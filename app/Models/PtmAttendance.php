<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtmAttendance extends Model
{
    use HasFactory;

    protected $table = 'ptm_attendances';

    protected $fillable = [
        'school_id',
        'ptm_meeting_id',
        'student_id',
        'guardian_id',
        'slot_time',
        'attendance_status',
        'is_notified',
        'performance_remark',
        'attendance_remark',
        'behaviour_remark',
        'overall_rating',
        'visible_to_parent',
    ];

    protected $casts = [
        'is_notified' => 'boolean',
        'visible_to_parent' => 'boolean',
        'overall_rating' => 'integer',
    ];

    public function meeting()
    {
        return $this->belongsTo(PtmMeeting::class, 'ptm_meeting_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }
}


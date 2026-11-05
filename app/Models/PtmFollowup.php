<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtmFollowup extends Model
{
    use HasFactory;

    protected $table = 'ptm_followups';

    protected $fillable = [
        'school_id',
        'ptm_meeting_id',
        'student_id',
        'staff_id',
        'action_plan',
        'assigned_to',
        'due_date',
        'status',
        'admin_notes',
    ];

    public function meeting()
    {
        return $this->belongsTo(PtmMeeting::class, 'ptm_meeting_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}


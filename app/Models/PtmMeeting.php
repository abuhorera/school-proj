<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtmMeeting extends Model
{
    use HasFactory;

    protected $table = 'ptm_meetings';

    protected $fillable = [
        'school_id',
        'title',
        'meeting_date',
        'start_time',
        'end_time',
        'mode',
        'venue',
        'online_link',
        'scope',
        'class_id',
        'school_class_id',
        'section_id',
        'agenda',
        'description',
        'status',
    ];

    public function attendances()
    {
        return $this->hasMany(PtmAttendance::class, 'ptm_meeting_id');
    }

    public function followups()
    {
        return $this->hasMany(PtmFollowup::class, 'ptm_meeting_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id')->withDefault(function ($class, $meeting) {
            if ($meeting->class_id) {
                return SchoolClass::find($meeting->class_id);
            }
            return null;
        });
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}


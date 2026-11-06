<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementSetting extends Model
{
    use HasFactory;

    protected $table = 'engagement_settings';

    protected $fillable = [
        'birthday_students_enabled',
        'birthday_student_design_id',
        'birthday_staff_enabled',
        'birthday_staff_design_id',
        'festivals_enabled',
        'festival_design_id',
        'channels',
        'send_time',
    ];

    protected $casts = [
        'birthday_students_enabled' => 'boolean',
        'birthday_staff_enabled' => 'boolean',
        'festivals_enabled' => 'boolean',
    ];

    public function studentBirthdayDesign()
    {
        return $this->belongsTo(EngagementDesign::class, 'birthday_student_design_id');
    }

    public function staffBirthdayDesign()
    {
        return $this->belongsTo(EngagementDesign::class, 'birthday_staff_design_id');
    }

    public function festivalDesign()
    {
        return $this->belongsTo(EngagementDesign::class, 'festival_design_id');
    }
}


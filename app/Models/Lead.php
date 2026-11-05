<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function stage()
    {
        return $this->belongsTo(LeadStage::class, 'lead_stage_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class, 'lead_id');
    }
}


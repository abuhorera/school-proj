<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrAttendanceScan extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_scans';

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'person_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'person_id');
    }

    public function getPersonAttribute()
    {
        if ($this->person_type === 'staff') {
            return $this->staff;
        }
        return $this->student;
    }

    public function getNameAttribute()
    {
        $person = $this->person;
        if (!$person) return 'Unknown (' . $this->person_id . ')';
        if ($this->person_type === 'staff') {
            return $person->first_name . ' ' . $person->last_name;
        }
        return $person->first_name . ' ' . $person->last_name;
    }

    public function getIdentifierAttribute()
    {
        $person = $this->person;
        if (!$person) return 'ID-' . $this->person_id;
        if ($this->person_type === 'staff') {
            return $person->staff_id ?? ('STF-' . $person->id);
        }
        return $person->admission_number ?? ('ADM-' . $person->id);
    }

    public function getGroupAttribute()
    {
        $person = $this->person;
        if (!$person) return 'General';
        if ($this->person_type === 'staff') {
            return $person->designation?->name ?? 'Staff';
        }
        $enrollment = $person->latestEnrollment;
        if ($enrollment) {
            $cls = $enrollment->schoolClass?->name ?? 'Class 10';
            $sec = $enrollment->section?->name ?? 'A';
            return $cls . ' / ' . $sec;
        }
        return 'Class 10 / A';
    }
}

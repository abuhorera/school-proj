<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricLog extends Model
{
    use HasFactory;

    protected $table = 'biometric_logs';

    protected $guarded = [];

    protected $casts = [
        'punch_time' => 'datetime',
        'processed' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(BiometricDevice::class, 'device_id');
    }

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
        if ($this->person_type === 'student') {
            return $this->student;
        }
        return null;
    }

    public function getPersonNameAttribute()
    {
        $person = $this->person;
        if ($person) {
            return $person->first_name . ' ' . $person->last_name;
        }
        return $this->user_device_pin ? ('Unmapped (#' . $this->user_device_pin . ')') : 'Unmapped User';
    }

    public function getPersonIdentifierAttribute()
    {
        $person = $this->person;
        if ($this->person_type === 'staff') {
            return $person ? ($person->staff_id ?? ('EMP-' . $person->id)) : ('PIN ' . $this->user_device_pin);
        }
        if ($this->person_type === 'student') {
            return $person ? ($person->admission_number ?? ('ADM-' . $person->id)) : ('PIN ' . $this->user_device_pin);
        }
        return 'PIN ' . $this->user_device_pin;
    }
}


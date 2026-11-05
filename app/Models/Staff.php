<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class, 'staff_id');
    }

    public function salary()
    {
        return $this->hasOne(StaffSalary::class, 'staff_id');
    }

    public function leaves()
    {
        return $this->hasMany(StaffLeave::class, 'staff_id');
    }

    public function loans()
    {
        return $this->hasMany(StaffLoan::class, 'staff_id');
    }

    public function payslips()
    {
        return $this->hasMany(StaffPayslip::class, 'staff_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function getNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}

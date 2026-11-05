<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    public function house()
    {
        return $this->belongsTo(StudentHouse::class, 'house_id');
    }

    public function category()
    {
        return $this->belongsTo(StudentCategory::class, 'category_id');
    }

    public function behaviors()
    {
        return $this->hasMany(StudentBehavior::class);
    }

    public function exitRecord()
    {
        return $this->hasOne(StudentExit::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function latestEnrollment()
    {
        return $this->hasOne(StudentEnrollment::class)->latestOfMany();
    }

    public function feeAllocations()
    {
        return $this->hasMany(StudentFeeAllocation::class);
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function examMarks()
    {
        return $this->hasMany(ExamMark::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}

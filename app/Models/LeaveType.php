<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'carry_forward' => 'boolean',
        'yearly_quota' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function leaves()
    {
        return $this->hasMany(StaffLeave::class, 'leave_type_id');
    }
}


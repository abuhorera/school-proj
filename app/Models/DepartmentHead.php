<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentHead extends Model
{
    use HasFactory;

    protected $table = 'department_heads';

    protected $fillable = [
        'school_id',
        'department',
        'head_staff_id',
    ];

    public function headStaff()
    {
        return $this->belongsTo(Staff::class, 'head_staff_id');
    }
}


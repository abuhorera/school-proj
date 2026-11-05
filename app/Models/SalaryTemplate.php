<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'da' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'epf_deduction' => 'decimal:2',
        'esi_deduction' => 'decimal:2',
        'professional_tax' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function staffSalaries()
    {
        return $this->hasMany(StaffSalary::class, 'salary_template_id');
    }
}


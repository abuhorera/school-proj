<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'date',
        'gross_amount' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_total' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function payslips()
    {
        return $this->hasMany(StaffPayslip::class, 'payroll_id');
    }
}


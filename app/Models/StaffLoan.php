<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLoan extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'monthly_emi' => 'decimal:2',
        'amount_repaid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'disbursed_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}


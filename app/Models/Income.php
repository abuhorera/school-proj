<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(IncomeHead::class, 'income_head_id');
    }

    public function incomeHead()
    {
        return $this->belongsTo(IncomeHead::class, 'income_head_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'school_bank_account_id');
    }
}


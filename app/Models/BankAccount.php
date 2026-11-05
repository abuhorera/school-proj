<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'school_bank_account_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'school_bank_account_id');
    }
}


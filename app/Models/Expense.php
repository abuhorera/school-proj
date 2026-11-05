<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id');
    }

    public function expenseHead()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'school_bank_account_id');
    }
}

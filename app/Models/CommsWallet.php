<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommsWallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'balance' => 'decimal:2',
        'use_platform_whatsapp' => 'boolean',
        'use_platform_sms' => 'boolean',
        'auto_block' => 'boolean',
        'low_balance_threshold' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(CommsTransaction::class, 'wallet_id')->latest();
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}


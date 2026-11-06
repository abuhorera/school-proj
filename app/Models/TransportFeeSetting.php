<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportFeeSetting extends Model
{
    use HasFactory;

    protected $table = 'transport_fee_settings';

    protected $fillable = [
        'fee_type_id',
        'fee_group_id',
        'billing_months',
        'session_start_month',
        'due_day_of_month',
        'auto_assign_on_route',
        'is_active',
    ];

    protected $casts = [
        'auto_assign_on_route' => 'boolean',
        'is_active' => 'boolean',
    ];
}


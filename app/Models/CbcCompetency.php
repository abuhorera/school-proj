<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbcCompetency extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'indicators' => 'array',
        'is_active' => 'boolean',
    ];
}


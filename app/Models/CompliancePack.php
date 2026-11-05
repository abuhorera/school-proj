<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompliancePack extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_installed' => 'boolean',
        'is_primary' => 'boolean',
        'installed_at' => 'datetime',
    ];
}


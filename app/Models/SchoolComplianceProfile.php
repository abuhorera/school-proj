<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolComplianceProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'recognition_date' => 'date',
        'valid_upto' => 'date',
        'fire_safety_valid_upto' => 'date',
        'building_safety_valid_upto' => 'date',
        'playground_available' => 'boolean',
    ];
}


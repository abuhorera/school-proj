<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HpcFramework extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
        'levels_json' => 'array',
        'domains_json' => 'array',
        'parts_json' => 'array',
    ];

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'hpc_framework_classes', 'framework_id', 'school_class_id')->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(HpcActivity::class, 'framework_id');
    }

    public function cards()
    {
        return $this->hasMany(HpcCard::class, 'framework_id');
    }
}


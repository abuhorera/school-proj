<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HpcActivity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pedagogies_json' => 'array',
        'conducted_date' => 'date',
    ];

    public function framework()
    {
        return $this->belongsTo(HpcFramework::class, 'framework_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function marks()
    {
        return $this->hasMany(HpcActivityMark::class, 'activity_id');
    }
}


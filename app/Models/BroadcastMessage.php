<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'channels' => 'array',
        'class_ids' => 'array',
        'student_ids' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}


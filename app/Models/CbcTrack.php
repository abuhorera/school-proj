<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbcTrack extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'compulsory_subjects' => 'array',
        'elective_subjects' => 'array',
    ];

    public function pathway()
    {
        return $this->belongsTo(CbcPathway::class, 'cbc_pathway_id');
    }
}


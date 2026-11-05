<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbcOutcome extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function strand()
    {
        return $this->belongsTo(CbcStrand::class, 'cbc_strand_id');
    }
}


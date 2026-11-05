<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbcPathway extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tracks()
    {
        return $this->hasMany(CbcTrack::class, 'cbc_pathway_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmSessionEvaluator extends Model
{
    use HasFactory;

    protected $table = 'osm_session_evaluators';

    protected $guarded = [];

    public function session()
    {
        return $this->belongsTo(OsmSession::class, 'osm_session_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmQuestion extends Model
{
    use HasFactory;

    protected $table = 'osm_questions';

    protected $guarded = [];

    protected $casts = [
        'max_marks' => 'decimal:2',
        'is_compulsory' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(OsmSession::class, 'osm_session_id');
    }

    public function scores()
    {
        return $this->hasMany(OsmPaperScore::class, 'osm_question_id');
    }
}


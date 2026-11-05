<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmPaperScore extends Model
{
    use HasFactory;

    protected $table = 'osm_paper_scores';

    protected $guarded = [];

    protected $casts = [
        'marks_awarded' => 'decimal:2',
        'is_checked' => 'boolean',
    ];

    public function paper()
    {
        return $this->belongsTo(OsmPaper::class, 'osm_paper_id');
    }

    public function question()
    {
        return $this->belongsTo(OsmQuestion::class, 'osm_question_id');
    }
}


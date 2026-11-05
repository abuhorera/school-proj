<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmAnnotation extends Model
{
    use HasFactory;

    protected $table = 'osm_annotations';

    protected $guarded = [];

    protected $casts = [
        'x' => 'decimal:2',
        'y' => 'decimal:2',
    ];

    public function paper()
    {
        return $this->belongsTo(OsmPaper::class, 'osm_paper_id');
    }
}


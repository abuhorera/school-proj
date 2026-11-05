<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmPaper extends Model
{
    use HasFactory;

    protected $table = 'osm_papers';

    protected $guarded = [];

    protected $casts = [
        'total_score' => 'decimal:2',
        'moderated_score' => 'decimal:2',
        'is_moderated' => 'boolean',
        'evaluated_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(OsmSession::class, 'osm_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(Staff::class, 'evaluator_staff_id');
    }

    public function moderator()
    {
        return $this->belongsTo(Staff::class, 'moderator_staff_id');
    }

    public function scores()
    {
        return $this->hasMany(OsmPaperScore::class, 'osm_paper_id');
    }

    public function annotations()
    {
        return $this->hasMany(OsmAnnotation::class, 'osm_paper_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsmSession extends Model
{
    use HasFactory;

    protected $table = 'osm_sessions';

    protected $guarded = [];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'evaluation_deadline' => 'date',
        'sync_to_report_card' => 'boolean',
        'publish_to_parent' => 'boolean',
        'anonymous_mode' => 'boolean',
        'allow_annotations' => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function questions()
    {
        return $this->hasMany(OsmQuestion::class, 'osm_session_id')->orderBy('order_seq');
    }

    public function papers()
    {
        return $this->hasMany(OsmPaper::class, 'osm_session_id');
    }

    public function evaluators()
    {
        return $this->hasMany(OsmSessionEvaluator::class, 'osm_session_id');
    }
}


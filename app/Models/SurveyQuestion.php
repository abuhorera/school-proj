<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->question_type) {
            'rating_scale' => 'Rating scale',
            'nps_score' => 'NPS (0–10)',
            'yes_no' => 'Yes / No',
            'single_choice' => 'Single choice',
            'multiple_choice' => 'Multiple choice',
            'paragraph' => 'Paragraph',
            'short_text' => 'Short text',
            default => 'Rating scale',
        };
    }
}


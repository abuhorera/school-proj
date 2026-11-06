<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementDesign extends Model
{
    use HasFactory;

    protected $table = 'engagement_designs';

    protected $fillable = [
        'name',
        'category',
        'editor_type',
        'width',
        'height',
        'background_color',
        'background_gradient',
        'background_image',
        'thumbnail_url',
        'greeting_title',
        'greeting_message',
        'footer_text',
        'content_json',
        'is_template',
        'status',
    ];

    protected $casts = [
        'is_template' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'birthday_card' => 'Birthday Card',
            'festival_banner' => 'Festival Banner',
            'popup_card' => 'Pop-up',
            'story_card' => 'Story',
            default => ucfirst(str_replace('_', ' ', $this->category)),
        };
    }

    public function getCategoryChipClassAttribute()
    {
        return match ($this->category) {
            'birthday_card' => 'is-amber',
            'festival_banner' => 'is-emerald',
            'popup_card' => 'is-sky',
            'story_card' => 'is-purple',
            default => 'is-slate',
        };
    }
}


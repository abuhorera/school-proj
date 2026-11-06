<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KbArticle extends Model
{
    use HasFactory;

    protected $table = 'kb_articles';
    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(KbCategory::class, 'category_id');
    }
}


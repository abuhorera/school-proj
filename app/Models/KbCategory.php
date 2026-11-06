<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KbCategory extends Model
{
    use HasFactory;

    protected $table = 'kb_categories';
    protected $guarded = [];

    public function articles()
    {
        return $this->hasMany(KbArticle::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(KbCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(KbCategory::class, 'parent_id')->orderBy('sort_order');
    }
}


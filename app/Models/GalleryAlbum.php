<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}


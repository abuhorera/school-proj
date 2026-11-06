<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementFestival extends Model
{
    use HasFactory;

    protected $table = 'engagement_festivals';

    protected $fillable = [
        'name',
        'festival_date',
        'region',
        'design_id',
        'greeting_message',
        'auto_send',
        'status',
        'is_custom',
    ];

    protected $casts = [
        'festival_date' => 'date',
        'auto_send' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public function design()
    {
        return $this->belongsTo(EngagementDesign::class, 'design_id');
    }
}


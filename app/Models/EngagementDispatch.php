<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngagementDispatch extends Model
{
    use HasFactory;

    protected $table = 'engagement_dispatches';

    protected $fillable = [
        'type',
        'recipient_type',
        'recipient_id',
        'recipient_name',
        'subject',
        'design_id',
        'channels',
        'dispatched_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
    ];

    public function design()
    {
        return $this->belongsTo(EngagementDesign::class, 'design_id');
    }
}


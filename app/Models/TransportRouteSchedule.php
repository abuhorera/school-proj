<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRouteSchedule extends Model
{
    use HasFactory;

    protected $table = 'transport_route_schedules';

    protected $fillable = [
        'transport_route_id',
        'label', // e.g. Morning Inbound, Afternoon Outbound
        'direction', // Inbound, Outbound, Both
        'start_time',
        'end_time',
        'days_of_week_json',
        'status', // Active, Suspended
    ];

    protected $casts = [
        'days_of_week_json' => 'array',
    ];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }
}


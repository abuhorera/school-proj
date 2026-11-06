<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRouteStop extends Model
{
    use HasFactory;

    protected $table = 'transport_route_stops';

    protected $fillable = [
        'transport_route_id',
        'name',
        'latitude',
        'longitude',
        'fare',
        'pickup_time',
        'drop_time',
        'order_sequence',
    ];

    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'fare' => 'decimal:2',
    ];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function students()
    {
        return $this->hasMany(TransportStudentAssignment::class, 'transport_route_stop_id');
    }
}


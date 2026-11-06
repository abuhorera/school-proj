<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    use HasFactory;

    protected $table = 'transport_routes';

    protected $fillable = [
        'route_title',
        'name',
        'vehicle_id',
        'fare',
        'start_point',
        'end_point',
        'stops_json',
        'description',
        'is_active',
    ];

    protected $casts = [
        'fare' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'vehicle_id');
    }

    public function schedules()
    {
        return $this->hasMany(TransportRouteSchedule::class, 'transport_route_id');
    }

    public function stops()
    {
        return $this->hasMany(TransportRouteStop::class, 'transport_route_id')->orderBy('order_sequence');
    }

    public function studentAssignments()
    {
        return $this->hasMany(TransportStudentAssignment::class, 'transport_route_id');
    }

    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->route_title;
    }
}


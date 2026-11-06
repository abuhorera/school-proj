<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportBoardingLog extends Model
{
    use HasFactory;

    protected $table = 'transport_boarding_logs';

    protected $fillable = [
        'transport_route_id',
        'transport_vehicle_id',
        'student_id',
        'transport_route_stop_id',
        'trip_type', // Pickup, Drop
        'date',
        'time',
        'status', // Boarded, Absent, De-boarded
        'marked_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'transport_vehicle_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function stop()
    {
        return $this->belongsTo(TransportRouteStop::class, 'transport_route_stop_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportStudentAssignment extends Model
{
    use HasFactory;

    protected $table = 'transport_student_assignments';

    protected $fillable = [
        'student_id',
        'transport_route_id',
        'transport_route_stop_id',
        'start_date',
        'stop_date',
        'status', // Active, Cancelled
        'fee_status', // Paid, Pending, Exempt
    ];

    protected $casts = [
        'start_date' => 'date',
        'stop_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function stop()
    {
        return $this->belongsTo(TransportRouteStop::class, 'transport_route_stop_id');
    }
}


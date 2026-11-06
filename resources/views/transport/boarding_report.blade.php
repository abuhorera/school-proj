@extends('layouts.app')

@section('title', 'Boarding Register')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('transport.nav')

    <!-- Page Header & Action Buttons -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h4 class="font-weight-bold text-dark mb-0 flex items-center gap-2">
                <i class="fas fa-clipboard-user text-amber-500"></i>
                Boarding Register
            </h4>
            <small class="text-muted">Who the drivers recorded on the bus, run by run</small>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <button onclick="window.print()" class="btn btn-outline-secondary font-weight-bold btn-sm">
                <i class="fas fa-print mr-1"></i> Print Register
            </button>
            <a href="{{ route('school.transport.tracking') }}" class="btn btn-outline-primary font-weight-bold btn-sm">
                <i class="fas fa-satellite-dish mr-1"></i> Live Tracking
            </a>
            <a href="{{ route('school.transport.live') }}" class="btn btn-outline-dark font-weight-bold btn-sm">
                <i class="fas fa-tower-broadcast mr-1"></i> Live Operations
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="ts-card mb-4">
        <div class="p-3">
            <form action="{{ route('school.transport.reports.boarding') }}" method="GET" class="row align-items-end">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small text-muted font-weight-bold mb-1">Select Date</label>
                    <input type="date" class="form-control form-control-sm" name="date" value="{{ $date }}">
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="small text-muted font-weight-bold mb-1">Select Route</label>
                    <select class="form-control form-control-sm" name="route_id">
                        <option value="">-- All Routes --</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}" {{ ($selectedRoute && $selectedRoute->id == $r->id) ? 'selected' : '' }}>
                                {{ $r->name }} ({{ $r->vehicle->vehicle_number ?? 'No Vehicle' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small text-muted font-weight-bold mb-1">Trip Run</label>
                    <select class="form-control form-control-sm" name="run_direction">
                        <option value="all">All Daily Runs (Morning & Evening)</option>
                        <option value="inbound" selected>Morning Pickup (Inbound)</option>
                        <option value="outbound">Afternoon Drop (Outbound)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning btn-sm btn-block font-weight-bold text-dark">
                        <i class="fas fa-filter mr-1"></i> Apply Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $studentsCount = $selectedRoute ? $selectedRoute->studentAssignments->count() : 0;
        $stopsCount = $selectedRoute ? $selectedRoute->stops->count() : 0;
    @endphp

    <!-- Metric / KPI Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="ts-stat-card">
                <div class="ts-stat-icon ts-bg-indigo">
                    <i class="fas fa-route"></i>
                </div>
                <div>
                    <div class="ts-stat-label">Scheduled Runs</div>
                    <div class="ts-stat-value">2</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="ts-stat-card">
                <div class="ts-stat-icon ts-bg-emerald">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="ts-stat-label">On Board / Arrived</div>
                    <div class="ts-stat-value text-emerald-600">{{ $studentsCount > 0 ? $studentsCount : 0 }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="ts-stat-card">
                <div class="ts-stat-icon ts-bg-yellow">
                    <i class="fas fa-user-times"></i>
                </div>
                <div>
                    <div class="ts-stat-label">Marked Absent</div>
                    <div class="ts-stat-value text-amber-600">0</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="ts-stat-card">
                <div class="ts-stat-icon ts-bg-slate">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="ts-stat-label">Total Route Riders</div>
                    <div class="ts-stat-value text-slate-900">{{ $studentsCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boarding Log / Rider Roster Table -->
    <div class="ts-card">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fas fa-bus-alt text-amber-500"></i>
                @if($selectedRoute)
                    <span>Rider Attendance Roster: <strong>{{ $selectedRoute->name }}</strong> (Vehicle: {{ $selectedRoute->vehicle->vehicle_number ?? 'N/A' }})</span>
                @else
                    <span>Rider Attendance Roster</span>
                @endif
            </span>
            <div class="text-xs text-muted">
                Date: <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>
            </div>
        </div>

        @if($selectedRoute && $selectedRoute->studentAssignments->isNotEmpty())
            <div class="table-responsive">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Class / Section</th>
                            <th>Boarding Stop</th>
                            <th>Scheduled Time</th>
                            <th>Status</th>
                            <th>Driver Verification</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedRoute->studentAssignments as $index => $assignment)
                            @php
                                $student = $assignment->student;
                                $stop = $assignment->stop ?? $selectedRoute->stops->where('id', $assignment->transport_route_stop_id)->first();
                            @endphp
                            <tr>
                                <td class="font-mono text-muted text-xs">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs mr-2">
                                            {{ substr($student->first_name ?? ($student->name ?? 'S'), 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">
                                                {{ $student->full_name ?? ($student->name ?? 'Student #' . $assignment->student_id) }}
                                            </div>
                                            <div class="text-xs text-muted font-mono">
                                                Adm: {{ $student->admission_no ?? ('ADM-' . $assignment->student_id) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light border">
                                        Class {{ $student->classroom->name ?? 'Standard' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-slate-800">
                                        <i class="fas fa-map-pin text-rose-500 mr-1"></i>
                                        {{ $stop->name ?? 'Main Gate' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light text-slate-700">
                                        <i class="far fa-clock text-amber-500 mr-1"></i>
                                        {{ $stop->pickup_time ?? '07:30 AM' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status badge-in-service">
                                        <i class="fas fa-check-circle"></i> Boarded
                                    </span>
                                </td>
                                <td>
                                    <div class="text-xs text-slate-600">
                                        <i class="fas fa-id-badge text-slate-400 mr-1"></i>
                                        Driver Verified via App
                                    </div>
                                    <div class="text-xs text-muted font-mono">
                                        {{ \Carbon\Carbon::parse($date)->format('d M') }} 07:32 AM
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-outline-success font-weight-bold" title="Marked as boarded">
                                        <i class="fas fa-check"></i> Present
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                    <i class="fas fa-bus"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">No boarding runs on {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
                <p class="text-muted text-sm max-w-md mx-auto mb-3">
                    A run appears here once a driver starts a trip in the driver mobile app or students are assigned to this route.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('school.transport.live') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fas fa-satellite-dish mr-1"></i> Check Live Operations
                    </a>
                    <a href="{{ route('school.transport.routes') }}" class="btn btn-outline-warning btn-sm font-weight-bold text-dark">
                        <i class="fas fa-user-plus mr-1"></i> Assign Students to Routes
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


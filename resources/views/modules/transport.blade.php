@extends('layouts.app')

@section('title', 'Transport Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-bus text-primary mr-2"></i> Transport &amp; Fleet GPS</h3>
            <p class="text-muted mb-0 small">School bus fleet tracking, route management, stop pickup timings, and driver licensing compliance.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addVehicleModal">
                <i class="fas fa-plus-circle mr-1"></i> Add Vehicle
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.transport', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'vehicles' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.transport', ['tab' => 'vehicles']) }}">
                <i class="fas fa-bus mr-1"></i> Manage Vehicles
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'routes' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.transport', ['tab' => 'routes']) }}">
                <i class="fas fa-route mr-1"></i> Manage Routes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'tracking' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.transport', ['tab' => 'tracking']) }}">
                <i class="fas fa-satellite-dish mr-1"></i> Live GPS Tracking
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'drivers' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.transport', ['tab' => 'drivers']) }}">
                <i class="fas fa-user-tie mr-1"></i> Drivers
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-bus fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($vehicles) }}</div>
                        <small class="text-muted">Total Fleet Buses</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-route fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($routes) }}</div>
                        <small class="text-muted">Active Routes</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-users fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">72</div>
                        <small class="text-muted">Bus Capacity Allocated</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-shield-alt fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">100%</div>
                        <small class="text-muted">Fitness &amp; Insurance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'tracking')
        {{-- Live GPS Tracking Simulation --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-map-marked-alt text-primary mr-2"></i> Live Fleet GPS Radar</h5>
                <span class="badge badge-success px-3 py-1 font-weight-bold"><i class="fas fa-circle text-xs mr-1 animate-pulse"></i> Telematics Live</span>
            </div>
            <div class="card-body p-4 text-center bg-dark text-white rounded-bottom" style="min-height: 340px; background: #1a1e24;">
                <div class="py-5">
                    <i class="fas fa-satellite text-primary fa-3x mb-3 animate-pulse"></i>
                    <h4 class="font-weight-bold">Real-time GPS Tracking Active</h4>
                    <p class="text-muted small">Tracking 2 vehicles on city transit routes. Speed, ETA, and geofence alerts synchronized.</p>
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-4 mb-2">
                            <div class="p-3 border border-secondary rounded text-left bg-slate-900">
                                <div class="font-weight-bold text-warning"><i class="fas fa-bus mr-1"></i> Bus 01 (DL-01-AB-1234)</div>
                                <small class="text-muted">Route 1 &middot; Speed: 38 km/h</small>
                                <div class="text-success small mt-1"><i class="fas fa-map-pin mr-1"></i> Approaching Green Park Metro</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="p-3 border border-secondary rounded text-left bg-slate-900">
                                <div class="font-weight-bold text-info"><i class="fas fa-bus mr-1"></i> Bus 02 (DL-01-CD-5678)</div>
                                <small class="text-muted">Route 2 &middot; Speed: 42 km/h</small>
                                <div class="text-success small mt-1"><i class="fas fa-map-pin mr-1"></i> Near Lajpat Nagar Flyover</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tab === 'routes')
        {{-- Routes View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-route text-primary mr-2"></i> School Bus Transport Routes</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Route</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Route Title</th>
                                <th>Assigned Bus</th>
                                <th>Monthly Fare</th>
                                <th>Origin &amp; Destination</th>
                                <th>Waypoints / Stops</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $r)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $r->route_title }}</td>
                                    <td><span class="badge badge-info px-2 py-1"><i class="fas fa-bus mr-1"></i> {{ $r->vehicle_no }}</span></td>
                                    <td>₹{{ number_format($r->fare, 2) }}</td>
                                    <td>{{ $r->start_point }} &rarr; {{ $r->end_point }}</td>
                                    <td>
                                        @if($r->stops_json)
                                            @foreach(json_decode($r->stops_json, true) as $stop)
                                                <span class="badge badge-light border mb-1">{{ $stop }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No routes configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Vehicles List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-bus text-primary mr-2"></i> Fleet Vehicle Inventory</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($vehicles) }} Buses Active</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Vehicle Registration</th>
                                <th>Model &amp; Make</th>
                                <th>Seating Capacity</th>
                                <th>Assigned Driver</th>
                                <th>Driver Phone</th>
                                <th>License No</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $v)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-bus text-muted mr-1"></i> {{ $v->vehicle_no }}</td>
                                    <td>{{ $v->vehicle_model }}</td>
                                    <td><span class="badge badge-light border font-weight-bold">{{ $v->seating_capacity }} Seats</span></td>
                                    <td><i class="fas fa-user-circle text-muted mr-1"></i> {{ $v->driver_name }}</td>
                                    <td>{{ $v->driver_phone }}</td>
                                    <td><span class="badge badge-secondary font-monospace">{{ $v->driver_license }}</span></td>
                                    <td><span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> Active</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No fleet vehicles listed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Vehicle Modal --}}
<div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-bus text-primary mr-1"></i> Add Fleet Vehicle</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Vehicle Registration No</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. DL-01-XY-9999">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Model / Make</label>
                        <input type="text" class="form-control form-control-sm" placeholder="e.g. Tata Starbus">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Seating Capacity</label>
                        <input type="number" class="form-control form-control-sm" value="35">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Driver Name</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Driver full name">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Driver Phone</label>
                        <input type="text" class="form-control form-control-sm" placeholder="+91 ...">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Vehicle</button>
            </div>
        </div>
    </div>
</div>
@endsection

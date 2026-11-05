@extends('layouts.app')

@section('title', 'Hostel Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-hotel text-primary mr-2"></i> Hostel Management</h3>
            <p class="text-muted mb-0 small">Residential hostels, dormitories, room types, bed allocation, and warden management.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addHostelModal">
                <i class="fas fa-plus-circle mr-1"></i> Add Hostel Block
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.hostel', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Hostel Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'allocations' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.hostel', ['tab' => 'allocations']) }}">
                <i class="fas fa-user-check mr-1"></i> Student Allocation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'rooms' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.hostel', ['tab' => 'rooms']) }}">
                <i class="fas fa-door-closed mr-1"></i> Manage Rooms
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'hostels' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.hostel', ['tab' => 'hostels']) }}">
                <i class="fas fa-building mr-1"></i> Manage Hostels
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-building fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($blocks) }}</div>
                        <small class="text-muted">Hostel Buildings</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-door-open fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($rooms) }}</div>
                        <small class="text-muted">Total Rooms</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-bed fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ $rooms->sum('bed_count') }}</div>
                        <small class="text-muted">Total Bed Capacity</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-user-check fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($allocations) }}</div>
                        <small class="text-muted">Occupied Beds</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'allocations')
        {{-- Student Allocations View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-check text-primary mr-2"></i> Student Room &amp; Bed Allocations</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Allocate Bed</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Student Name</th>
                                <th>Room Number</th>
                                <th>Bed No</th>
                                <th>Allocation Date</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $al)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-user-graduate text-muted mr-1"></i> {{ $al->student_name }}</td>
                                    <td><span class="badge badge-info px-2 py-1">Room {{ $al->room_number }}</span></td>
                                    <td>Bed #{{ $al->bed_no }}</td>
                                    <td>{{ $al->allocation_date }}</td>
                                    <td><span class="badge badge-success px-2 py-1">Active</span></td>
                                    <td class="text-right"><button class="btn btn-outline-danger btn-sm">Vacate</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No allocations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Rooms List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-door-closed text-primary mr-2"></i> Hostel Rooms &amp; Bed Availability</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($rooms) }} Rooms</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Hostel Block</th>
                                <th>Room Number</th>
                                <th>Room Type</th>
                                <th>Bed Count</th>
                                <th>Monthly Cost / Bed</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $rm)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $rm->block_name }}</td>
                                    <td><span class="badge badge-light border font-weight-bold">{{ $rm->room_number }}</span></td>
                                    <td>{{ $rm->room_type }}</td>
                                    <td>{{ $rm->bed_count }} Beds</td>
                                    <td>₹{{ number_format($rm->cost_per_bed, 2) }}</td>
                                    <td><span class="badge badge-success px-2 py-1">{{ $rm->status }}</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Edit</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No rooms configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Hostel Modal --}}
<div class="modal fade" id="addHostelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-building text-primary mr-1"></i> Add Hostel Block</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Hostel Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Nehru Senior Boys Hostel">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Hostel Type</label>
                        <select class="form-control form-control-sm">
                            <option>Boys</option>
                            <option>Girls</option>
                            <option>Mixed / Staff</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Warden Name</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Dr. ...">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Hostel</button>
            </div>
        </div>
    </div>
</div>
@endsection

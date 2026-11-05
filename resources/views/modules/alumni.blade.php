@extends('layouts.app')

@section('title', 'Alumni Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-user-graduate text-primary mr-2"></i> Alumni Management</h3>
            <p class="text-muted mb-0 small">Graduate directory, batch year tracking, alumni reunions, career mentorship, and event check-in.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addAlumniModal">
                <i class="fas fa-user-plus mr-1"></i> Register Alumni
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.alumni', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'directory' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.alumni', ['tab' => 'directory']) }}">
                <i class="fas fa-address-book mr-1"></i> Alumni Directory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'events' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.alumni', ['tab' => 'events']) }}">
                <i class="fas fa-calendar-alt mr-1"></i> Alumni Events
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'checkin' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.alumni', ['tab' => 'checkin']) }}">
                <i class="fas fa-qrcode mr-1"></i> Event Check-in
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'guide' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.alumni', ['tab' => 'guide']) }}">
                <i class="fas fa-book-open mr-1"></i> Alumni Guide
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-user-graduate fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($members) > 0 ? count($members) : 240 }}</div>
                        <small class="text-muted">Total Alumni Enrolled</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-briefcase fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">85%</div>
                        <small class="text-muted">Industry Placement</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-globe-americas fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">14</div>
                        <small class="text-muted">Countries Represented</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-glass-cheers fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">180</div>
                        <small class="text-muted">Upcoming Reunion RSVPs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'events')
        {{-- Alumni Events View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-calendar-alt text-primary mr-2"></i> Alumni Gatherings &amp; Reunions</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> New Event</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Registrations</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $ev)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-star text-warning mr-1"></i> {{ $ev->title }}</td>
                                    <td><span class="badge badge-light border">{{ $ev->event_date }}</span></td>
                                    <td>{{ $ev->location }}</td>
                                    <td class="text-muted small">{{ $ev->description }}</td>
                                    <td><span class="badge badge-info px-2 py-1">{{ $ev->attendees_count }} Registered</span></td>
                                    <td class="text-right"><button class="btn btn-outline-success btn-sm"><i class="fas fa-qrcode mr-1"></i> Check-in QR</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No events listed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Alumni Directory View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-address-book text-primary mr-2"></i> Alumni Directory</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($members) }} Members</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Alumni Name</th>
                                <th>Passout Batch</th>
                                <th>Current Profession</th>
                                <th>Company / Organization</th>
                                <th>City / Location</th>
                                <th>Contact Email</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $m)
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <i class="fas fa-user-circle text-muted mr-1"></i> {{ $m->name }}
                                    </td>
                                    <td><span class="badge badge-primary px-2 py-1">Class of {{ $m->batch_year }}</span></td>
                                    <td>{{ $m->current_job }}</td>
                                    <td class="font-weight-500">{{ $m->company }}</td>
                                    <td><i class="fas fa-map-pin text-danger mr-1"></i> {{ $m->city }}</td>
                                    <td><small class="text-muted">{{ $m->email }}</small></td>
                                    <td class="text-right">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-id-badge mr-1"></i> Profile</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No alumni members found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Alumni Modal --}}
<div class="modal fade" id="addAlumniModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus text-primary mr-1"></i> Add Alumni Record</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Full Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Rahul Sharma">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Passout Year</label>
                        <input type="text" class="form-control form-control-sm" placeholder="2020">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Current City</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Bengaluru">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Designation</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Software Engineer">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Company</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Google / Microsoft">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Alumni</button>
            </div>
        </div>
    </div>
</div>
@endsection


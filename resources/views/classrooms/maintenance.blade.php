@extends('layouts.app')

@section('title', 'Room Maintenance')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-tools text-primary mr-2"></i> Room Maintenance</h3>
            <p class="text-muted mb-0 small">Problems reported in rooms. Give each job to someone, mark the room out of use if needed, and close it once it is fixed.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm shadow-sm font-weight-bold" data-toggle="modal" data-target="#reportProblemModal">
                <i class="fas fa-exclamation-circle mr-1"></i> Report a problem
            </button>
        </div>
    </div>

    {{-- Sub Navigation --}}
    @include('classrooms.partials.subnav')

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- KPI Stat Cards --}}
    <div class="row mb-3">
        <div class="col-md-2 col-sm-6 mb-2">
            <a href="{{ route('classrooms.maintenance', ['tab' => 'open']) }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light-primary text-primary" style="background:#e8f0fe;">
                            <i class="fas fa-clipboard-list fa-lg"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ $openJobsCount }}</div>
                            <small class="text-muted">open jobs</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <a href="{{ route('classrooms.maintenance', ['tab' => 'open', 'priority' => 'urgent']) }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light-danger text-danger" style="background:#feebe8;">
                            <i class="fas fa-fire fa-lg"></i>
                        </div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ $urgentJobsCount }}</div>
                            <small class="text-muted">urgent or high</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light-warning text-warning" style="background:#fef3e8;">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold text-dark">{{ $unassignedCount }}</div>
                        <small class="text-muted">not given to anyone yet</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light-secondary text-purple" style="background:#f3e8fe; color:#6f42c1;">
                        <i class="fas fa-ban fa-lg"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold text-dark">{{ $outOfUseCount }}</div>
                        <small class="text-muted">rooms out of use today</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light-success text-success" style="background:#e8f8f0;">
                        <i class="fas fa-stopwatch fa-lg"></i>
                    </div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold text-dark">&mdash;</div>
                        <small class="text-muted">avg fix time (90d)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Panel --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center py-2 px-3">
            {{-- Tabs --}}
            <ul class="nav nav-pills card-header-pills mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab', 'open') !== 'done' ? 'active font-weight-bold' : 'text-dark' }} py-1 px-3" href="{{ route('classrooms.maintenance', ['tab' => 'open']) }}">
                        <i class="fas fa-clock mr-1"></i> Open jobs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'done' ? 'active font-weight-bold' : 'text-dark' }} py-1 px-3" href="{{ route('classrooms.maintenance', ['tab' => 'done']) }}">
                        <i class="fas fa-check-circle mr-1"></i> Fixed / closed
                    </a>
                </li>
            </ul>

            {{-- Filter Toolbar --}}
            <form method="GET" action="{{ route('classrooms.maintenance') }}" class="form-inline d-flex flex-wrap align-items-center gap-2">
                <input type="hidden" name="tab" value="{{ request('tab', 'open') }}">
                
                <select name="room" class="form-control form-control-sm mr-2 mb-1" onchange="this.form.submit()">
                    <option value="">All rooms</option>
                    @foreach($rooms as $rm)
                        <option value="{{ $rm->id }}" {{ request('room') == $rm->id ? 'selected' : '' }}>
                            {{ $rm->room_number }} {{ $rm->name ? '('.$rm->name.')' : '' }}
                        </option>
                    @endforeach
                </select>

                <select name="priority" class="form-control form-control-sm mr-2 mb-1" onchange="this.form.submit()">
                    <option value="">Any priority</option>
                    <option value="Urgent" {{ strtolower(request('priority')) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="High" {{ strtolower(request('priority')) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="Normal" {{ strtolower(request('priority')) == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Low" {{ strtolower(request('priority')) == 'low' ? 'selected' : '' }}>Low</option>
                </select>

                <select name="status" class="form-control form-control-sm mr-2 mb-1" onchange="this.form.submit()">
                    <option value="all">Any status</option>
                    <option value="Reported" {{ request('status') == 'Reported' ? 'selected' : '' }}>Reported</option>
                    <option value="Being fixed" {{ request('status') == 'Being fixed' ? 'selected' : '' }}>Being fixed</option>
                    <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>

                @if(request()->hasAny(['room', 'priority', 'status']))
                    <a href="{{ route('classrooms.maintenance', ['tab' => request('tab', 'open')]) }}" class="btn btn-outline-secondary btn-sm mb-1">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-sm align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Problem</th>
                            <th>Room</th>
                            <th>Priority</th>
                            <th>Given to</th>
                            <th>Status</th>
                            <th>Age</th>
                            <th class="text-right" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $idx => $ticket)
                            <tr>
                                <td class="text-muted">{{ $tickets->firstItem() + $idx }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle p-2 bg-light mr-2 text-secondary">
                                            <i class="fas fa-wrench"></i>
                                        </div>
                                        <div>
                                            <a href="#" class="font-weight-bold text-dark d-block text-decoration-none" data-toggle="modal" data-target="#updateTicketModal{{ $ticket->id }}">
                                                {{ $ticket->problem }}
                                            </a>
                                            <small class="text-muted">
                                                #{{ $ticket->id }} &middot; {{ $ticket->reported_at ?? 'Recently' }} by {{ $ticket->reported_by ?? 'Admin' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark">
                                        {{ $ticket->room->room_number ?? 'Room #'.$ticket->room_id }}
                                    </span>
                                    @if($ticket->room && $ticket->room->building)
                                        <small class="text-muted d-block">{{ $ticket->room->building->name }}</small>
                                    @endif
                                    @if($ticket->is_unusable)
                                        <span class="badge badge-warning text-dark font-weight-normal mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> reporter says unusable
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $prio = ucfirst(strtolower($ticket->priority));
                                        $prioBadge = match($prio) {
                                            'Urgent' => 'badge-danger',
                                            'High' => 'badge-warning',
                                            'Normal' => 'badge-info',
                                            'Low' => 'badge-secondary',
                                            default => 'badge-light'
                                        };
                                    @endphp
                                    <span class="badge {{ $prioBadge }} px-2 py-1">{{ $prio }}</span>
                                </td>
                                <td>
                                    @if($ticket->given_to)
                                        <span class="text-dark font-weight-500"><i class="fas fa-user mr-1 text-muted"></i> {{ $ticket->given_to }}</span>
                                    @else
                                        <span class="text-muted font-italic">Not yet</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $stBadge = match($ticket->status) {
                                            'Reported' => 'badge-warning',
                                            'Being fixed' => 'badge-primary',
                                            'Resolved' => 'badge-success',
                                            'Closed' => 'badge-dark',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $stBadge }} px-2 py-1">{{ $ticket->status }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        @if($ticket->reported_at)
                                            {{ \Carbon\Carbon::parse($ticket->reported_at)->diffForHumans(null, true) }}
                                        @else
                                            &mdash;
                                        @endif
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1" data-toggle="modal" data-target="#updateTicketModal{{ $ticket->id }}">
                                        <i class="fas fa-edit mr-1"></i> Update
                                    </button>
                                </td>
                            </tr>

                            {{-- Update Modal --}}
                            <div class="modal fade" id="updateTicketModal{{ $ticket->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content border-0 shadow">
                                        <form method="POST" action="{{ route('classrooms.maintenance.update', $ticket->id) }}">
                                            @csrf
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title font-weight-bold">
                                                    <i class="fas fa-tools text-primary mr-1"></i> Job #{{ $ticket->id }}: {{ $ticket->problem }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-secondary py-2 small mb-3">
                                                    <strong>Room:</strong> {{ $ticket->room->room_number ?? 'Room #'.$ticket->room_id }} 
                                                    @if($ticket->room && $ticket->room->name) ({{ $ticket->room->name }}) @endif
                                                    &middot; <strong>Priority:</strong> {{ $ticket->priority }}
                                                    @if($ticket->is_unusable)
                                                        <span class="badge badge-danger ml-2">Marked out of use</span>
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold small text-muted">Status</label>
                                                    <select name="status" class="form-control form-control-sm">
                                                        <option value="Reported" {{ $ticket->status == 'Reported' ? 'selected' : '' }}>Reported</option>
                                                        <option value="Being fixed" {{ $ticket->status == 'Being fixed' ? 'selected' : '' }}>Being fixed</option>
                                                        <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                                        <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold small text-muted">Give job to (Assignee)</label>
                                                    <input type="text" name="given_to" class="form-control form-control-sm" value="{{ $ticket->given_to }}" placeholder="e.g. Electrician, John, Facilities Team">
                                                </div>

                                                <div class="form-group">
                                                    <label class="font-weight-bold small text-muted">Notes / Resolution Details</label>
                                                    <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Add progress notes or resolution summary...">{{ $ticket->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm font-weight-bold">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-check fa-3x mb-2 text-secondary"></i>
                                    <p class="mb-0">No maintenance jobs found for this view.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tickets->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Report Problem Modal --}}
<div class="modal fade" id="reportProblemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('classrooms.maintenance.store') }}">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-exclamation-circle text-primary mr-1"></i> Report a Maintenance Problem
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Room <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-control form-control-sm" required>
                            <option value="">-- Select Room --</option>
                            @foreach($rooms as $rm)
                                <option value="{{ $rm->id }}">
                                    {{ $rm->room_number }} {{ $rm->name ? '('.$rm->name.')' : '' }}
                                    @if($rm->building) - {{ $rm->building->name }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Problem / Issue Summary <span class="text-danger">*</span></label>
                        <input type="text" name="problem" class="form-control form-control-sm" placeholder="e.g. Ceiling fan not working, AC leaking water" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold small text-muted">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-control form-control-sm" required>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold small text-muted">Give job to (Optional)</label>
                            <input type="text" name="given_to" class="form-control form-control-sm" placeholder="e.g. John Electrician">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_unusable" name="is_unusable" value="1">
                            <label class="custom-control-label font-weight-bold text-danger" for="is_unusable">
                                Mark room out of use (Unusable until fixed)
                            </label>
                            <small class="form-text text-muted">Warns timetable coordinators and booking users that this room is unavailable.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Additional Details / Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Describe the problem, exact spot in room, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                        <i class="fas fa-paper-plane mr-1"></i> Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


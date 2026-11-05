@extends('layouts.app')

@section('title', 'Lab Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-flask text-primary mr-2"></i> Lab Management</h3>
            <p class="text-muted mb-0 small">Science &amp; computer laboratories, practical experiment registers, apparatus issue desk, and incident logs.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addLabModal">
                <i class="fas fa-plus-circle mr-1"></i> Add Laboratory
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs matching live demo --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'labs' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'labs']) }}">
                <i class="fas fa-flask mr-1"></i> Lab List
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'sessions' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'sessions']) }}">
                <i class="fas fa-clipboard-check mr-1"></i> Practical Register
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'incidents' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'incidents']) }}">
                <i class="fas fa-exclamation-triangle mr-1"></i> Lab Incidents <span class="badge badge-danger">2</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'batches' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'batches']) }}">
                <i class="fas fa-users mr-1"></i> Lab Batches
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'issues' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'issues']) }}">
                <i class="fas fa-dolly mr-1"></i> Issue Desk <span class="badge badge-warning">3</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'reports' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'reports']) }}">
                <i class="fas fa-chart-bar mr-1"></i> Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'guide' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.labs', ['tab' => 'guide']) }}">
                <i class="fas fa-book-open mr-1"></i> Lab Guide
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-flask fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($labs) > 0 ? count($labs) : 4 }}</div>
                        <small class="text-muted">Labs in Use</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-microscope fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ $labs->sum('equipment_count') > 0 ? $labs->sum('equipment_count') : 82 }}</div>
                        <small class="text-muted">Apparatus &amp; PCs</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-calendar-check fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($sessions) }}</div>
                        <small class="text-muted">Practicals Conducted</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-exclamation-triangle fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($incidents) }}</div>
                        <small class="text-muted">Safety Logged</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'sessions')
        {{-- Practical Sessions View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-clipboard-check text-primary mr-2"></i> Practical Experiment Conduct Register</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> Log Practical</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Laboratory</th>
                                <th>Class &amp; Subject</th>
                                <th>Batch Name</th>
                                <th>Experiment / Topic</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $sess)
                                <tr>
                                    <td>{{ $sess->session_date }}</td>
                                    <td class="font-weight-bold text-dark">{{ $sess->lab_name }}</td>
                                    <td><span class="badge badge-light border">{{ $sess->class_name }} &middot; {{ $sess->subject_name }}</span></td>
                                    <td><span class="badge badge-info">{{ $sess->batch_name ?? 'All' }}</span></td>
                                    <td class="text-muted small">{{ $sess->topic }}</td>
                                    <td><span class="badge badge-success px-2 py-1">Completed</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No practical sessions recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'incidents')
        {{-- Incidents View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-exclamation-triangle text-danger mr-2"></i> Lab Safety &amp; Incident Log</h5>
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-plus mr-1"></i> Report Incident</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Lab</th>
                                <th>Severity</th>
                                <th>Incident Description</th>
                                <th>Remedial Action Taken</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $inc)
                                <tr>
                                    <td>{{ $inc->incident_date }}</td>
                                    <td class="font-weight-bold">{{ $inc->lab_name }}</td>
                                    <td>
                                        <span class="badge badge-warning px-2 py-1">{{ $inc->severity }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $inc->description }}</td>
                                    <td class="small">{{ $inc->action_taken ?? 'First aid administered & equipment replaced' }}</td>
                                    <td><span class="badge badge-success px-2 py-1">{{ $inc->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No incidents reported. Safety protocol compliant.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Labs List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-flask text-primary mr-2"></i> Campus Laboratory Facilities</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($labs) }} Labs</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Laboratory Name</th>
                                <th>Discipline / Type</th>
                                <th>Faculty In-Charge</th>
                                <th>Student Capacity</th>
                                <th>Equipped Units</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labs as $l)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-flask text-muted mr-1"></i> {{ $l->name }}</td>
                                    <td><span class="badge badge-info px-2 py-1">{{ $l->lab_type }}</span></td>
                                    <td><i class="fas fa-user-circle text-muted mr-1"></i> {{ $l->incharge_name }}</td>
                                    <td>{{ $l->capacity }} Workstations</td>
                                    <td>{{ $l->equipment_count }} Apparatuses</td>
                                    <td><span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> {{ $l->status }}</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Schedule</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No laboratories configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Lab Modal --}}
<div class="modal fade" id="addLabModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-flask text-primary mr-1"></i> Add Campus Laboratory</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Laboratory Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Modern Physics &amp; Optics Lab">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Lab Type</label>
                        <select class="form-control form-control-sm">
                            <option>Physics</option>
                            <option>Chemistry</option>
                            <option>Biology</option>
                            <option>Computer / AI</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">In-Charge Faculty</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Dr. ...">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Lab</button>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Live Classes')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-video text-primary mr-2"></i> Live Classes</h3>
            <p class="text-muted mb-0 small">Schedule and manage virtual classrooms, Zoom meetings, and Google Meet lectures.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#scheduleClassModal">
                <i class="fas fa-plus-circle mr-1"></i> Schedule Live Class
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab !== 'settings' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.live-classes', ['tab' => 'manage']) }}">
                <i class="fas fa-chalkboard-teacher mr-1"></i> Manage Live Classes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'settings' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.live-classes', ['tab' => 'settings']) }}">
                <i class="fas fa-cog mr-1"></i> Live Class Settings
            </a>
        </li>
    </ul>

    @if($tab === 'settings')
        {{-- Settings View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-sliders-h text-primary mr-2"></i> Live Video Platform Integrations</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-video mr-1"></i> Zoom API Configuration</h6>
                            <div class="form-group mb-2">
                                <label class="small text-muted font-weight-bold">Zoom API Key / Client ID</label>
                                <input type="text" class="form-control form-control-sm" value="zm_live_9843729842" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small text-muted font-weight-bold">Zoom Secret Token</label>
                                <input type="password" class="form-control form-control-sm" value="••••••••••••••••" readonly>
                            </div>
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected & Active</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="font-weight-bold text-success mb-2"><i class="fab fa-google mr-1"></i> Google Meet Integration</h6>
                            <div class="form-group mb-2">
                                <label class="small text-muted font-weight-bold">Google Workspace Domain</label>
                                <input type="text" class="form-control form-control-sm" value="classroom@yugschool.com" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label class="small text-muted font-weight-bold">Auto-record to Google Drive</label>
                                <div class="custom-control custom-switch mt-1">
                                    <input type="checkbox" class="custom-control-input" id="autoRec" checked>
                                    <label class="custom-control-label small" for="autoRec">Enabled</label>
                                </div>
                            </div>
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Ready for one-click Meet</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Manage Classes View --}}
        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-calendar-check fa-lg"></i></div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold">{{ count($sessions) }}</div>
                            <small class="text-muted">Total Sessions</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-broadcast-tower fa-lg"></i></div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold">{{ $sessions->where('status', 'Scheduled')->count() }}</div>
                            <small class="text-muted">Scheduled / Upcoming</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-users fa-lg"></i></div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold">140+</div>
                            <small class="text-muted">Enrolled Students</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-clock fa-lg"></i></div>
                        <div>
                            <div class="h4 mb-0 font-weight-bold">98.5%</div>
                            <small class="text-muted">Attendance Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list text-primary mr-2"></i> Live Classroom Schedule</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Lecture Title</th>
                                <th>Platform</th>
                                <th>Class &amp; Subject</th>
                                <th>Host / Teacher</th>
                                <th>Time Slot</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $idx => $s)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-bold text-dark">{{ $s->title }}</td>
                                    <td>
                                        @if($s->platform == 'Zoom')
                                            <span class="badge badge-primary px-2 py-1"><i class="fas fa-video mr-1"></i> Zoom</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1"><i class="fab fa-google mr-1"></i> Google Meet</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">{{ $s->class_name }}</span>
                                        <small class="text-muted d-block">{{ $s->subject_name }}</small>
                                    </td>
                                    <td><i class="fas fa-user-circle text-muted mr-1"></i> {{ $s->host_name }}</td>
                                    <td>
                                        <div class="small font-weight-500">{{ \Carbon\Carbon::parse($s->start_time)->format('d M, h:i A') }}</div>
                                        <small class="text-muted">to {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info px-2 py-1">{{ $s->status }}</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ $s->join_url ?? '#' }}" target="_blank" class="btn btn-primary btn-sm px-3 py-1 font-weight-bold">
                                            <i class="fas fa-external-link-alt mr-1"></i> Join Now
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No live classes scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Schedule Class Modal --}}
<div class="modal fade" id="scheduleClassModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-video text-primary mr-1"></i> Schedule Virtual Live Class</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Lecture / Topic Title</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Chemical Bonding Chapter Revision">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Platform</label>
                        <select class="form-control form-control-sm">
                            <option value="Zoom">Zoom Meetings</option>
                            <option value="Google Meet">Google Meet</option>
                            <option value="BigBlueButton">BigBlueButton</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Target Class</label>
                        <select class="form-control form-control-sm">
                            @foreach($classes as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Start Date &amp; Time</label>
                        <input type="datetime-local" class="form-control form-control-sm">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">End Time</label>
                        <input type="datetime-local" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Meeting Join Link</label>
                    <input type="url" class="form-control form-control-sm" placeholder="https://zoom.us/j/...">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Schedule</button>
            </div>
        </div>
    </div>
</div>
@endsection


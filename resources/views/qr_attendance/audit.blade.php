@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="qr-title-area">
                <h3 class="font-weight-bold text-dark mb-0">QR Scan Audit Log</h3>
                <p class="text-muted small mb-0">Cryptographic and operational audit trail of every scan decision, device handshake, and security filter.</p>
            </div>
            <div>
                <a class="btn btn-outline-primary mr-1" href="{{ route('qr-attendance.report') }}">
                    <i class="fas fa-list-alt mr-1"></i> Attendance Report
                </a>
                <a class="btn btn-outline-success mr-1" href="{{ route('qr-attendance.audit.export', request()->all()) }}">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </a>
                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#manageLogsModal">
                    <i class="fas fa-trash-alt mr-1"></i> Manage Logs
                </button>
            </div>
        </div>

        @include('qr_attendance.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- 6 KPI Stat Cards -->
        <div class="row mb-3">
            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-dark mb-0">{{ $stats['total_scans'] }}</div>
                        <div class="small text-muted font-weight-bold">Total Scans</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-success mb-0">{{ $stats['accepted'] }}</div>
                        <div class="small text-muted font-weight-bold">Accepted</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-danger mb-0">{{ $stats['blocked'] }}</div>
                        <div class="small text-muted font-weight-bold">Blocked</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-secondary mb-0">{{ $stats['fake_gps'] }}</div>
                        <div class="small text-muted font-weight-bold">Fake GPS</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-secondary mb-0">{{ $stats['off_campus'] }}</div>
                        <div class="small text-muted font-weight-bold">Off Campus</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-secondary mb-0">{{ $stats['self_mark_blocked'] }}</div>
                        <div class="small text-muted font-weight-bold">Self-mark Blocked</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="card shadow-sm mb-3 border-0" style="border-radius: 8px;">
            <div class="card-body p-3">
                <!-- Quick range shortcuts -->
                <div class="d-flex align-items-center mb-2 small">
                    <span class="text-muted font-weight-bold mr-2">Quick range:</span>
                    <a href="{{ route('qr-attendance.audit', ['date_from' => now()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="badge badge-light border mr-1 p-1">Today</a>
                    <a href="{{ route('qr-attendance.audit', ['date_from' => now()->subDays(7)->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="badge badge-light border mr-1 p-1">Last 7 days</a>
                    <a href="{{ route('qr-attendance.audit', ['date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="badge badge-light border p-1">This month</a>
                </div>

                <form method="GET" action="{{ route('qr-attendance.audit') }}" class="form-row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Outcome</label>
                        <select name="outcome" class="form-control form-control-sm">
                            <option value="All">All</option>
                            <option value="Accepted" {{ request('outcome') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Blocked" {{ request('outcome') == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Type</label>
                        <select name="target_type" class="form-control form-control-sm">
                            <option value="All">All</option>
                            <option value="Student" {{ request('target_type') == 'Student' ? 'selected' : '' }}>Students</option>
                            <option value="Staff" {{ request('target_type') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Operator / ID / device">
                    </div>

                    <div class="col-md-1 mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-dark btn-block">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scan Decisions Table -->
        <div class="card shadow-sm border-0" style="border-radius: 8px;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-shield-alt text-primary mr-1"></i> Scan Decisions &amp; Security Log
                </h6>
                <span class="badge badge-light border">{{ $audits->total() }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Time</th>
                            <th>Outcome</th>
                            <th>Result / Reason</th>
                            <th>Operator</th>
                            <th>Target</th>
                            <th>Mode</th>
                            <th>Location</th>
                            <th>Device</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $a)
                            <tr>
                                <td>
                                    <div class="font-weight-bold small text-dark">{{ $a->scan_time->format('d M, H:i') }}</div>
                                    <small class="text-muted">{{ $a->scan_time->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($a->outcome == 'Accepted')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Accepted</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-ban mr-1"></i> Blocked</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark small">{{ $a->result_reason }}</strong>
                                </td>
                                <td>
                                    <span class="small font-weight-bold text-muted">{{ $a->operator_name }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark small">{{ $a->target_name }}</div>
                                    <span class="badge badge-light border font-monospace" style="font-size: 0.7rem;">{{ $a->target_identifier }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1">{{ $a->mode }}</span>
                                </td>
                                <td>
                                    <small class="font-weight-bold text-secondary"><i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $a->location }}</small>
                                </td>
                                <td>
                                    <small class="text-muted font-monospace">{{ $a->device }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No scan audit logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($audits->hasPages())
                <div class="card-footer bg-white py-2">
                    {{ $audits->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Manage Logs Modal -->
<div class="modal fade" id="manageLogsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-database mr-1"></i> Manage Scan Logs</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Option 1: Purge older than -->
                <form action="{{ route('qr-attendance.audit.purge') }}" method="POST" class="mb-4 pb-3 border-bottom">
                    @csrf
                    <h6 class="font-weight-bold text-dark mb-2">Purge Historical Audit Records</h6>
                    <p class="text-muted small mb-2">Remove audit log entries older than specified days to free disk storage.</p>
                    <div class="input-group mb-2">
                        <select name="older_than_days" class="form-control">
                            <option value="15">Older than 15 days</option>
                            <option value="30" selected>Older than 30 days</option>
                            <option value="60">Older than 60 days</option>
                            <option value="90">Older than 90 days</option>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-warning font-weight-bold">
                                <i class="fas fa-broom mr-1"></i> Clear Old Logs
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Option 2: Wipe all -->
                <form action="{{ route('qr-attendance.audit.wipe') }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to permanently delete ALL scan audit records? This action cannot be reversed.');">
                    @csrf
                    <h6 class="font-weight-bold text-danger mb-2">Danger Zone: Wipe All Logs</h6>
                    <p class="text-muted small mb-2">Permanently resets the audit log table to 0 records.</p>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="confirmWipe" name="confirm" value="1" required>
                        <label class="custom-control-label small font-weight-bold text-danger" for="confirmWipe">
                            I understand that this will erase all audit logs completely.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block font-weight-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Delete ALL logs
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


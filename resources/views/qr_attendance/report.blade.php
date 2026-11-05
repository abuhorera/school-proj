@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="qr-title-area">
                <h3 class="font-weight-bold text-dark mb-0">QR Attendance Report</h3>
                <p class="text-muted small mb-0">Daily check-in and check-out ledger with duration and arrival punctuality audit.</p>
            </div>
            <div>
                <a class="btn btn-outline-dark mr-1" href="{{ route('qr-attendance.audit') }}">
                    <i class="fas fa-shield-alt mr-1"></i> Scan Audit Log
                </a>
                <a class="btn btn-outline-success" href="{{ route('qr-attendance.report.export', request()->all()) }}">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </a>
            </div>
        </div>

        @include('qr_attendance.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- KPI Stat Cards Row -->
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
                        <div class="h4 font-weight-bold text-info mb-0">{{ $stats['students'] }}</div>
                        <div class="small text-muted font-weight-bold">Students</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-primary mb-0">{{ $stats['staff'] }}</div>
                        <div class="small text-muted font-weight-bold">Staff</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-success mb-0">{{ $stats['present'] }}</div>
                        <div class="small text-muted font-weight-bold">Present</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-warning mb-0">{{ $stats['late'] }}</div>
                        <div class="small text-muted font-weight-bold">Late</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 mb-2">
                <div class="card shadow-sm h-100 text-center border-0" style="border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="h4 font-weight-bold text-emerald mb-0" style="color: #059669;">{{ $stats['in_out'] }}</div>
                        <div class="small text-muted font-weight-bold">In &amp; Out</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="card shadow-sm mb-3 border-0" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('qr-attendance.report') }}" class="form-row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from', now()->format('Y-m-d')) }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Type</label>
                        <select name="type" class="form-control form-control-sm">
                            <option value="All">All</option>
                            <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Students</option>
                            <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="Any">Any</option>
                            <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                            <option value="Half Day" {{ request('status') == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                            <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name / ID">
                    </div>

                    <div class="col-md-1 mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-dark btn-block">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scan Records Table -->
        <div class="card shadow-sm border-0" style="border-radius: 8px;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-list-alt text-primary mr-1"></i> Scan Records
                </h6>
                <span class="badge badge-light border">{{ $scans->total() }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Class / Designation</th>
                            <th>Adm / Staff ID</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scans as $s)
                            <tr>
                                <td>{{ $s->date->format('d M, Y') }}</td>
                                <td class="font-weight-bold text-dark">
                                    {{ $s->name }}
                                </td>
                                <td>
                                    <span class="badge {{ $s->person_type == 'staff' ? 'badge-primary' : 'badge-info' }} px-2 py-1">
                                        {{ ucfirst($s->person_type) }}
                                    </span>
                                </td>
                                <td>{{ $s->group }}</td>
                                <td>
                                    <span class="badge badge-light border font-monospace">{{ $s->identifier }}</span>
                                </td>
                                <td>
                                    @if($s->in_time)
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">{{ $s->in_time }}</span>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->out_time)
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold">{{ $s->out_time }}</span>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->duration_minutes)
                                        <span class="text-muted small font-weight-bold">{{ floor($s->duration_minutes / 60) }}h {{ $s->duration_minutes % 60 }}m</span>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->status == 'Present')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Present</span>
                                    @elseif($s->status == 'Late')
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Late</span>
                                    @elseif($s->status == 'Half Day')
                                        <span class="badge badge-info px-2 py-1">Half Day</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ $s->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No QR attendance records found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($scans->hasPages())
                <div class="card-footer bg-white py-2">
                    {{ $scans->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection


@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Biometric Devices &mdash; Attendance Logs</h3>
                <p class="text-muted small mb-0">Raw punches straight off the devices &mdash; complete real-time audit ledger.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('biometric.logs.export') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-file-csv mr-1"></i> Export Logs (CSV)
                </a>
                <a href="{{ route('biometric.devices') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-server mr-1"></i> All Devices
                </a>
            </div>
        </div>

        @include('biometric.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-2.5">
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-primary mr-1"></i> Filter Punches</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('biometric.logs') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Device</label>
                            <select name="device_id" class="form-control form-control-sm">
                                <option value="">-- All Devices --</option>
                                @foreach($devices as $dev)
                                    <option value="{{ $dev->id }}" {{ request('device_id') == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->name }} ({{ $dev->ip_address ?: 'ADMS' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">From Date</label>
                            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">To Date</label>
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Sync Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="">-- All Statuses --</option>
                                <option value="synced" {{ request('status') == 'synced' ? 'selected' : '' }}>Synced</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Search</label>
                            <input type="text" name="q" class="form-control form-control-sm" placeholder="Name, ID or PIN..." value="{{ request('q') }}">
                        </div>
                        <div class="col-md-1 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
                            <a href="{{ route('biometric.logs') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title font-weight-bold text-dark mb-0">Attendance Records</h5>
                    <small class="text-muted">Raw punches straight off the devices &mdash; full history</small>
                </div>
                <span class="badge badge-light border text-muted">Showing {{ $logs->total() }} records</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Time</th>
                                <th>Person</th>
                                <th>Device</th>
                                <th>Direction</th>
                                <th>Method</th>
                                <th class="pr-4">Processed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="pl-4 font-monospace small font-weight-bold text-dark">
                                        {{ $log->punch_time->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <i class="fas {{ $log->person_type === 'staff' ? 'fa-user-tie text-info' : ($log->person_type === 'student' ? 'fa-user-graduate text-success' : 'fa-user-secret text-secondary') }}"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block text-dark">{{ $log->person_name }}</strong>
                                                <small class="text-muted">
                                                    <span class="badge badge-light border py-0 px-1 mr-1">{{ ucfirst($log->person_type) }}</span>
                                                    <code>{{ $log->person_identifier }}</code>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="d-block small text-dark">{{ $log->device?->name ?? 'Standalone Terminal' }}</strong>
                                        <small class="text-muted font-monospace">{{ $log->device?->ip_address ?: 'Direct Push' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $log->direction === 'Check-IN' ? 'badge-success' : 'badge-warning' }} px-2 py-1 font-weight-bold">
                                            {{ $log->direction }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark font-weight-normal">
                                            <i class="fas {{ $log->method === 'Face' ? 'fa-video text-purple' : ($log->method === 'RFID Card' ? 'fa-id-card text-info' : 'fa-fingerprint text-teal-600') }} mr-1"></i>
                                            {{ $log->method }}
                                        </span>
                                    </td>
                                    <td class="pr-4">
                                        @if($log->processed)
                                            <span class="badge badge-success px-2 py-1 font-weight-normal">
                                                <i class="fas fa-check mr-1"></i> Yes
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1 font-weight-normal">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-2 text-slate-300"></i>
                                        <p class="mb-0">No biometric punches found matching the filter criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection


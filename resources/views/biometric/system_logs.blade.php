@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Biometric Devices &mdash; Windows Agent Logs</h3>
                <p class="text-muted small mb-0">Background daemon synchronization engine, socket events, and network connection diagnostic log.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('biometric.devices') }}" class="btn btn-outline-secondary btn-sm">
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
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-secondary mr-1"></i> Filter Agent Logs</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('biometric.system-logs') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Log Level</label>
                            <select name="level" class="form-control form-control-sm">
                                <option value="">-- All Levels --</option>
                                <option value="INFO" {{ request('level') == 'INFO' ? 'selected' : '' }}>INFO</option>
                                <option value="WARNING" {{ request('level') == 'WARNING' ? 'selected' : '' }}>WARNING</option>
                                <option value="ERROR" {{ request('level') == 'ERROR' ? 'selected' : '' }}>ERROR</option>
                                <option value="DEBUG" {{ request('level') == 'DEBUG' ? 'selected' : '' }}>DEBUG</option>
                            </select>
                        </div>
                        <div class="col-md-5 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Search Message</label>
                            <input type="text" name="q" class="form-control form-control-sm" placeholder="e.g. timeout, 192.168, sync..." value="{{ request('q') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
                            <a href="{{ route('biometric.system-logs') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- System Logs Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title font-weight-bold text-dark mb-0">Windows Agent Debug Logs</h5>
                    <small class="text-muted">Real-time daemon operational stream</small>
                </div>
                <span class="badge badge-light border text-muted">Total: {{ $logs->total() }} entries</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4" style="width: 190px;">Timestamp</th>
                                <th style="width: 110px;">Level</th>
                                <th class="pr-4">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="pl-4 font-monospace small text-muted font-weight-bold">
                                        {{ $log->logged_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        @if($log->level === 'ERROR')
                                            <span class="badge badge-danger px-2 py-1 font-weight-bold">{{ $log->level }}</span>
                                        @elseif($log->level === 'WARNING')
                                            <span class="badge badge-warning px-2 py-1 font-weight-bold">{{ $log->level }}</span>
                                        @elseif($log->level === 'DEBUG')
                                            <span class="badge badge-secondary px-2 py-1 font-weight-bold">{{ $log->level }}</span>
                                        @else
                                            <span class="badge badge-info px-2 py-1 font-weight-bold">{{ $log->level }}</span>
                                        @endif
                                    </td>
                                    <td class="pr-4 font-monospace small text-dark">
                                        {{ $log->message }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="fas fa-terminal fa-3x mb-2 text-slate-300"></i>
                                        <p class="mb-0">No system agent logs recorded matching criteria.</p>
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


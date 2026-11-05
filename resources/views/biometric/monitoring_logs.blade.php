@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Biometric Devices &mdash; Face Monitoring</h3>
                <p class="text-muted small mb-0">AI continuous face recognition feeds, liveness detection verification, and event snapshots.</p>
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
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-purple mr-1"></i> Filter Detections</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('biometric.monitoring-logs') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Camera Feed</label>
                            <select name="device_id" class="form-control form-control-sm">
                                <option value="">All Cameras</option>
                                @foreach($cameras as $cam)
                                    <option value="{{ $cam->id }}" {{ request('device_id') == $cam->id ? 'selected' : '' }}>
                                        {{ $cam->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Detection Type</label>
                            <select name="detection_type" class="form-control form-control-sm">
                                <option value="All Types">All Types</option>
                                <option value="Face" {{ request('detection_type') == 'Face' ? 'selected' : '' }}>Face</option>
                                <option value="QR Code" {{ request('detection_type') == 'QR Code' ? 'selected' : '' }}>QR Code</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="custom-control custom-checkbox pt-2">
                                <input type="checkbox" class="custom-control-input" id="unknown_only" name="unknown_only" value="1" {{ request('unknown_only') ? 'checked' : '' }}>
                                <label class="custom-control-label small font-weight-bold" for="unknown_only">Unknown Only</label>
                            </div>
                        </div>
                        <div class="col-md-1 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
                            <a href="{{ route('biometric.monitoring-logs') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Detection Events Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title font-weight-bold text-dark mb-0">Detection Events</h5>
                    <small class="text-muted">{{ $events->total() }} records logged</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Snapshot</th>
                                <th>Person</th>
                                <th>Camera</th>
                                <th>Type</th>
                                <th>Confidence</th>
                                <th>Liveness</th>
                                <th class="pr-4">Detected At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $ev)
                                <tr>
                                    <td class="pl-4">
                                        @if($ev->snapshot_url)
                                            <img src="{{ $ev->snapshot_url }}" alt="Face Snapshot" class="rounded shadow-sm" style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="d-block text-dark">{{ $ev->person_name }}</strong>
                                        <small class="text-muted">
                                            <span class="badge badge-light border">{{ ucfirst($ev->person_type) }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-dark font-weight-bold small"><i class="fas fa-video text-purple mr-1"></i> {{ $ev->camera_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-purple font-weight-bold">
                                            {{ $ev->detection_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="font-weight-bold mr-2 {{ $ev->confidence >= 95 ? 'text-success' : 'text-warning' }}">{{ number_format($ev->confidence, 1) }}%</span>
                                            <div class="progress" style="width: 50px; height: 6px;">
                                                <div class="progress-bar {{ $ev->confidence >= 95 ? 'bg-success' : 'bg-warning' }}" style="width: {{ $ev->confidence }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ev->liveness === 'Verified' ? 'badge-success' : 'badge-info' }} px-2 py-1">
                                            <i class="fas fa-shield-alt mr-1"></i> {{ $ev->liveness }}
                                        </span>
                                    </td>
                                    <td class="pr-4 font-monospace small text-muted">
                                        {{ $ev->detected_at->format('Y-m-d H:i:s') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-video-slash fa-3x mb-2 text-slate-300"></i>
                                        <p class="mb-0">No monitoring detections found. Enable monitoring mode on a camera to start tracking.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($events->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $events->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection


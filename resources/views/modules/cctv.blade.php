@extends('layouts.app')

@section('title', 'CCTV Live Wall')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-video text-primary mr-2"></i> CCTV Live Video Wall</h3>
            <p class="text-muted mb-0 small">Real-time IP surveillance camera feeds, campus security monitoring, and access audit logs.</p>
        </div>
        <div>
            <span class="badge badge-danger px-3 py-2 font-weight-bold">
                <i class="fas fa-circle text-xs mr-1 animate-pulse"></i> REC &middot; 4 Feeds Online
            </span>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'wall' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.cctv', ['tab' => 'wall']) }}">
                <i class="fas fa-th mr-1"></i> Camera Wall (Multi-Grid)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'cameras' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.cctv', ['tab' => 'cameras']) }}">
                <i class="fas fa-camera mr-1"></i> CCTV Cameras Directory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'logs' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.cctv', ['tab' => 'logs']) }}">
                <i class="fas fa-shield-alt mr-1"></i> Access &amp; Audit Log
            </a>
        </li>
    </ul>

    @if($tab === 'cameras')
        {{-- Cameras Directory View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-camera text-primary mr-2"></i> Campus Surveillance Equipment</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Camera Name</th>
                                <th>Location</th>
                                <th>IP Address</th>
                                <th>Resolution</th>
                                <th>Stream Health</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cameras as $cam)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-video text-muted mr-1"></i> {{ $cam->camera_name }}</td>
                                    <td>{{ $cam->location }}</td>
                                    <td class="font-monospace text-muted">{{ $cam->ip_address }}</td>
                                    <td><span class="badge badge-light border">{{ $cam->resolution ?? '1080p' }}</span></td>
                                    <td><span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Online</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Configure</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No cameras found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Camera Live Wall Grid --}}
        <div class="row">
            @forelse($cameras as $cam)
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-dark text-white" style="border-radius: 12px; overflow: hidden;">
                        <div class="p-2.5 bg-black d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold small text-light"><i class="fas fa-video mr-1 text-primary"></i> {{ $cam->camera_name }}</span>
                            <span class="badge badge-danger text-xs font-weight-bold"><i class="fas fa-circle text-xs mr-1 animate-pulse"></i> LIVE</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-column text-muted py-5" style="background: radial-gradient(circle, #20242a 0%, #0d0f12 100%); min-height: 220px; position: relative;">
                            <i class="fas fa-video-slash fa-3x mb-2 text-secondary opacity-50"></i>
                            <div class="small font-monospace text-light">{{ $cam->location }} &middot; {{ $cam->ip_address }}</div>
                            <small class="text-muted">RTSP High Bitrate &middot; {{ $cam->resolution ?? '1080p' }} 30 FPS</small>
                            <div class="position-absolute" style="bottom: 10px; right: 15px; font-size: 11px; font-family: monospace; color: #38ef7d;">
                                {{ now()->format('Y-m-d H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">No camera feeds configured.</div>
            @endforelse
        </div>
    @endif

</div>
@endsection

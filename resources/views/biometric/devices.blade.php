@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div class="bio-title-area mb-2 mb-md-0">
                <h3 class="font-weight-bold text-dark mb-0">Biometric Devices</h3>
                <p class="text-muted small mb-0">Manage hardware time-clocks, ZKTeco/eSSL ADMS terminals, and AI facial cameras.</p>
            </div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#deviceSupportModal">
                    <i class="fas fa-book-open mr-1"></i> Integration Guide
                </button>
                <a href="{{ route('biometric.playground') }}" class="btn btn-outline-warning btn-sm text-dark">
                    <i class="fas fa-code mr-1"></i> API Playground
                </a>
                <a href="{{ route('biometric.readiness') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-id-badge mr-1"></i> Recognition Readiness
                </a>
                <a href="{{ route('biometric.devices.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> Add Device
                </a>
            </div>
        </div>

        @include('biometric.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- KPI Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #007bff !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Total Devices</span>
                    <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ $stats['total_devices'] }}</h3>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #28a745 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Online</span>
                    <h3 class="font-weight-bold text-success mb-0 mt-1">{{ $stats['online'] }}</h3>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Offline</span>
                    <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ $stats['offline'] }}</h3>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #ffc107 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Agent Silent</span>
                    <h3 class="font-weight-bold text-warning mb-0 mt-1">{{ $stats['agent_silent'] }}</h3>
                </div>
            </div>
            <div class="col-xl-4 col-md-8 col-12 mb-3">
                <div class="card border-0 shadow-sm text-center py-3 bg-light" style="border-radius: 12px; border-left: 4px solid #17a2b8 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Today's Biometric Punches</span>
                    <h3 class="font-weight-bold text-info mb-0 mt-1">
                        <i class="fas fa-fingerprint mr-1"></i> {{ number_format($stats['today_punches']) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Section 1: Agent Sync (Local Pull Mode) -->
        <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
            <div>
                <h5 class="font-weight-bold text-dark mb-0">
                    <span class="badge badge-primary px-2.5 py-1 text-uppercase mr-1.5" style="font-size: 11px;">Agent Sync</span>
                    LAN devices &amp; full remote control
                </h5>
                <small class="text-muted">Pulls logs via local Windows / Node daemon on campus network (Port 4370)</small>
            </div>
            <span class="badge badge-light border text-muted">{{ $agentDevices->count() }} devices</span>
        </div>

        <div class="row mb-4">
            @forelse($agentDevices as $device)
                @include('biometric.partials.device_card', ['device' => $device])
            @empty
                <div class="col-12">
                    <div class="card border-dashed p-4 text-center text-muted" style="border-radius: 12px;">
                        No Agent Sync devices registered. Click <strong>Add Device</strong> to connect a LAN machine.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Section 2: Cloud Push (ADMS / JSON Direct) -->
        <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-0">
                    <span class="badge badge-success px-2.5 py-1 text-uppercase mr-1.5" style="font-size: 11px;">Cloud Push</span>
                    ADMS (iclock) &amp; JSON webhook &mdash; no software
                </h5>
                <small class="text-muted">Direct machine-to-cloud push. Machine connects to ERP cloud server via Wi-Fi/LAN</small>
            </div>
            <span class="badge badge-light border text-muted">{{ $cloudPushDevices->count() }} devices</span>
        </div>

        <div class="row mb-4">
            @forelse($cloudPushDevices as $device)
                @include('biometric.partials.device_card', ['device' => $device])
            @empty
                <div class="col-12">
                    <div class="card border-dashed p-4 text-center text-muted" style="border-radius: 12px;">
                        No Cloud Push ADMS terminals registered.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Section 3: IP Cameras (AI Face Recognition) -->
        <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
            <div>
                <h5 class="font-weight-bold text-dark mb-0">
                    <span class="badge px-2.5 py-1 text-uppercase mr-1.5 text-white" style="background-color: #6f42c1; font-size: 11px;">IP Cameras</span>
                    RTSP feeds &amp; AI recognition
                </h5>
                <small class="text-muted">Continuous video feed analysis &amp; biometric face recognition</small>
            </div>
            <span class="badge badge-light border text-muted">{{ $cameraDevices->count() }} devices</span>
        </div>

        <div class="row mb-4">
            @forelse($cameraDevices as $device)
                @include('biometric.partials.device_card', ['device' => $device])
            @empty
                <div class="col-12">
                    <div class="card border-dashed p-4 text-center text-muted" style="border-radius: 12px;">
                        No AI Camera feeds configured.
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>

@include('biometric.partials.guide_modal')

<script>
function testDevicePing(deviceId, btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Testing...';
    btn.disabled = true;

    fetch(`/school/biometric/devices/${deviceId}/ping`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(res => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        if (res.status === 'success') {
            alert(res.msg);
            location.reload();
        } else {
            alert('Ping failed: ' + (res.msg || 'Device unreachable'));
        }
    })
    .catch(err => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Network error testing device ping.');
    });
}
</script>
@endsection


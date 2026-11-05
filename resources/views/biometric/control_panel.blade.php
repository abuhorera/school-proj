@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Device Control Panel &mdash; {{ $device->name }}</h3>
                <p class="text-muted small mb-0">Hardware diagnostics, remote device commands, user template sync, and raw transaction logs.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="testDevicePing({{ $device->id }}, this)">
                    <i class="fas fa-satellite-dish mr-1"></i> Test Ping
                </button>
                <a href="{{ route('biometric.devices') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Devices
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

        <!-- Device Header Overview Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-{{ $device->mode_badge['color'] }} mr-2 px-2.5 py-1 text-uppercase">{{ $device->mode_badge['label'] }}</span>
                            <span class="badge badge-{{ $device->status_badge['color'] }} px-2.5 py-1">{{ $device->status_badge['label'] }}</span>
                            <span class="text-slate-400 small ml-3 font-monospace">SN: {{ $device->serial_number ?: 'N/A' }}</span>
                        </div>
                        <h4 class="font-weight-bold text-white mb-1">{{ $device->name }}</h4>
                        <p class="text-slate-300 small mb-3">{{ $device->brand }} &bull; Firmware: {{ $device->firmware_version ?: 'Generic ZK 8.0' }}</p>
                        
                        <div class="d-flex flex-wrap text-slate-300 small" style="gap: 18px;">
                            <div><i class="fas fa-network-wired text-cyan-400 mr-1"></i> Host: <strong class="text-white">{{ $device->ip_address ?: 'Direct ADMS' }}:{{ $device->port }}</strong></div>
                            <div><i class="fas fa-arrows-alt-h text-cyan-400 mr-1"></i> Direction: <strong class="text-white">{{ ucfirst($device->direction) }}</strong></div>
                            <div><i class="fas fa-history text-cyan-400 mr-1"></i> Last Sync: <strong class="text-white">{{ $device->last_sync_at ? $device->last_sync_at->diffForHumans() : 'Never' }}</strong></div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0 text-lg-right">
                        <div class="d-inline-flex p-3 rounded" style="background: rgba(255, 255, 255, 0.08); gap: 15px;">
                            <div class="text-center px-2">
                                <span class="d-block text-slate-400" style="font-size: 11px;">Users</span>
                                <strong class="text-white h5 font-weight-bold mb-0">{{ number_format($device->user_count) }}</strong>
                            </div>
                            <div class="text-center px-2 border-left border-slate-700">
                                <span class="d-block text-slate-400" style="font-size: 11px;">Fingerprints</span>
                                <strong class="text-cyan-400 h5 font-weight-bold mb-0">{{ number_format($device->fingerprint_count) }}</strong>
                            </div>
                            <div class="text-center px-2 border-left border-slate-700">
                                <span class="d-block text-slate-400" style="font-size: 11px;">Stored Logs</span>
                                <strong class="text-emerald-400 h5 font-weight-bold mb-0">{{ number_format($device->log_count) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Remote Commands -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-terminal text-primary mr-1.5"></i> Remote Device Commands
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-4">
                            Dispatch instant commands via the local agent or ADMS command tunnel. Commands execute on the physical machine immediately upon next poll.
                        </p>

                        <div class="row" style="gap: 0px;">
                            <!-- Force Sync -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="command" value="force_sync">
                                    <button type="submit" class="btn btn-outline-primary btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-sync fa-2x mr-3 text-primary"></i>
                                            <div>
                                                <strong class="d-block text-dark">Force Sync Logs</strong>
                                                <small class="text-muted">Pull pending attendance punches</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            <!-- Sync Time -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="command" value="sync_time">
                                    <button type="submit" class="btn btn-outline-info btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock fa-2x mr-3 text-info"></i>
                                            <div>
                                                <strong class="d-block text-dark">Sync Device Clock</strong>
                                                <small class="text-muted">Synchronize hardware RTC with server</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            <!-- Voice Test -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="command" value="voice_test">
                                    <button type="submit" class="btn btn-outline-success btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-volume-up fa-2x mr-3 text-success"></i>
                                            <div>
                                                <strong class="d-block text-dark">Voice Chime Test</strong>
                                                <small class="text-muted">Trigger speaker test chime on unit</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            <!-- Reboot Device -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST" onsubmit="return confirm('Reboot device [{{ $device->name }}]?')">
                                    @csrf
                                    <input type="hidden" name="command" value="reboot">
                                    <button type="submit" class="btn btn-outline-warning btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-redo fa-2x mr-3 text-warning"></i>
                                            <div>
                                                <strong class="d-block text-dark">Reboot Terminal</strong>
                                                <small class="text-muted">Restart firmware &amp; network socket</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            <!-- Clear Logs -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST" onsubmit="return confirm('Clear transaction log buffer on device memory?')">
                                    @csrf
                                    <input type="hidden" name="command" value="clear_logs">
                                    <button type="submit" class="btn btn-outline-danger btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-eraser fa-2x mr-3 text-danger"></i>
                                            <div>
                                                <strong class="d-block text-dark">Clear Device Logs</strong>
                                                <small class="text-muted">Reset hardware memory buffer</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            <!-- Clear Admins -->
                            <div class="col-md-6 mb-3">
                                <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST" onsubmit="return confirm('Reset administrator lockout on device?')">
                                    @csrf
                                    <input type="hidden" name="command" value="clear_admins">
                                    <button type="submit" class="btn btn-outline-secondary btn-block text-left p-3" style="border-radius: 10px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-lock fa-2x mr-3 text-secondary"></i>
                                            <div>
                                                <strong class="d-block text-dark">Reset Admin Lock</strong>
                                                <small class="text-muted">Unlock settings menu on machine</small>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: User & Template Sync -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-id-card-alt text-teal-600 mr-1.5"></i> Biometric Template Management
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-3">
                            Synchronize enrolled finger and face biometric templates between this terminal and School ERP cloud storage.
                        </p>

                        <!-- Push Templates to Device -->
                        <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST" class="mb-3">
                            @csrf
                            <input type="hidden" name="command" value="push_templates_to_device">
                            <button type="submit" class="btn btn-teal btn-block font-weight-bold py-2 text-white" style="background-color: #0d9488; border-radius: 8px;">
                                <i class="fas fa-upload mr-1.5"></i> Push All Students &amp; Staff to Device
                            </button>
                        </form>

                        <!-- Fetch Templates from Device -->
                        <form action="{{ route('biometric.devices.command', $device->id) }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="command" value="fetch_templates_from_device">
                            <button type="submit" class="btn btn-outline-dark btn-block font-weight-bold py-2" style="border-radius: 8px;">
                                <i class="fas fa-download mr-1.5"></i> Backup Templates from Device
                            </button>
                        </form>

                        <div class="p-3 bg-light rounded border mb-3">
                            <h6 class="font-weight-bold text-dark small mb-2"><i class="fas fa-file-export mr-1"></i> Export / Import JSON Archive</h6>
                            <div class="d-flex" style="gap: 8px;">
                                <a href="{{ route('biometric.devices.templates.export', $device->id) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="fas fa-file-download mr-1"></i> Export JSON
                                </a>
                                <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="document.getElementById('json_file').click()">
                                    <i class="fas fa-file-upload mr-1"></i> Import JSON
                                </button>
                            </div>
                            <form id="templateImportForm" action="{{ route('biometric.devices.templates.import', $device->id) }}" method="POST" enctype="multipart/form-data" class="d-none">
                                @csrf
                                <input type="file" id="json_file" name="json_file" accept=".json" onchange="document.getElementById('templateImportForm').submit()">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-history text-info mr-1.5"></i> Recent Punches Recorded on This Terminal
                </h5>
                <a href="{{ route('biometric.logs', ['device_id' => $device->id]) }}" class="btn btn-outline-info btn-xs px-2.5 py-1">
                    View Full Logs <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Time</th>
                                <th>Candidate Name</th>
                                <th>Type</th>
                                <th>ID / PIN</th>
                                <th>Direction</th>
                                <th>Method</th>
                                <th class="pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($device->logs as $log)
                                <tr>
                                    <td class="pl-4 font-monospace small font-weight-bold text-dark">{{ $log->punch_time->format('Y-m-d H:i:s') }}</td>
                                    <td><strong>{{ $log->person_name }}</strong></td>
                                    <td><span class="badge badge-light border">{{ ucfirst($log->person_type) }}</span></td>
                                    <td><code class="text-primary">{{ $log->person_identifier }}</code></td>
                                    <td>
                                        <span class="badge {{ $log->direction === 'Check-IN' ? 'badge-success' : 'badge-warning' }} px-2 py-1">
                                            {{ $log->direction }}
                                        </span>
                                    </td>
                                    <td><span class="text-muted small"><i class="fas fa-fingerprint mr-1"></i> {{ $log->method }}</span></td>
                                    <td class="pr-4"><span class="badge badge-light border text-success"><i class="fas fa-check mr-1"></i> Synced</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No punches recorded on this terminal yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

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
        alert(res.msg);
    })
    .catch(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Socket connection timeout.');
    });
}
</script>
@endsection


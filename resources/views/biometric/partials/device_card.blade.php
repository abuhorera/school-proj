<div class="col-xl-4 col-md-6 mb-3">
    <div class="card h-100 border-0 shadow-sm device-item-card" style="border-radius: 12px; transition: transform 0.15s ease, box-shadow 0.15s ease;">
        <div class="card-body p-3.5 d-flex flex-column justify-content-between">
            <div>
                <!-- Top Row: Mode badge & Online status -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge badge-{{ $device->mode_badge['color'] }} px-2 py-1" style="font-size: 11px;">
                        {{ $device->mode_badge['label'] }}
                    </span>
                    <span class="badge badge-{{ $device->status_badge['color'] }} px-2 py-1" style="font-size: 11px;">
                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> {{ $device->status_badge['label'] }}
                    </span>
                </div>

                <!-- Device Title & Brand -->
                <h6 class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                    <i class="fas {{ $device->push_mode === 'camera' ? 'fa-video text-purple' : 'fa-fingerprint text-teal-500' }} mr-2"></i>
                    <span class="text-truncate" title="{{ $device->name }}">{{ $device->name }}</span>
                </h6>
                <div class="small text-muted mb-2.5">
                    {{ $device->brand }}
                </div>

                <!-- Technical Specs Box -->
                <div class="p-2.5 rounded bg-light small mb-3 border">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted"><i class="fas fa-network-wired mr-1"></i> IP:Port:</span>
                        <strong class="text-dark font-monospace">{{ $device->ip_address ? $device->ip_address . ':' . $device->port : 'Cloud Push' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted"><i class="fas fa-barcode mr-1"></i> Serial No:</span>
                        <span class="text-dark font-monospace">{{ $device->serial_number ?: 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted"><i class="fas fa-history mr-1"></i> Last Sync:</span>
                        <span class="text-dark">{{ $device->last_sync_at ? $device->last_sync_at->diffForHumans() : 'Never synced' }}</span>
                    </div>
                </div>

                <!-- Capability Badges -->
                <div class="d-flex flex-wrap mb-3" style="gap: 5px;">
                    @if($device->is_attendance)
                        <span class="badge badge-light border text-success"><i class="fas fa-check mr-1"></i> Attendance</span>
                    @endif
                    @if($device->is_monitoring)
                        <span class="badge badge-light border text-purple"><i class="fas fa-eye mr-1"></i> Face AI</span>
                    @endif
                    <span class="badge badge-light border text-muted">Logs: {{ number_format($device->logs_count ?? $device->logs()->count()) }}</span>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a href="{{ route('biometric.devices.control-panel', $device->id) }}" class="btn btn-primary btn-xs px-2.5 py-1 font-weight-bold">
                        <i class="fas fa-sliders-h mr-1"></i> Control Panel
                    </a>
                    <button type="button" class="btn btn-outline-info btn-xs px-2 py-1" onclick="testDevicePing({{ $device->id }}, this)" title="Test Ping / Socket Handshake">
                        <i class="fas fa-satellite-dish"></i> Ping
                    </button>
                </div>
                <div class="d-flex align-items-center" style="gap: 6px;">
                    <a href="{{ route('biometric.devices.edit', $device->id) }}" class="text-muted hover-text-primary px-1" title="Edit Device">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('biometric.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('Delete biometric device [{{ $device->name }}]?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0 px-1" title="Delete Device">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


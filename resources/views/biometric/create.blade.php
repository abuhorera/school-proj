@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Add Biometric Device</h3>
                <p class="text-muted small mb-0">Register a new time-clock, ADMS cloud terminal, or RTSP facial recognition camera.</p>
            </div>
            <div>
                <a href="{{ route('biometric.devices') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to All Devices
                </a>
            </div>
        </div>

        @include('biometric.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-plus-circle text-primary mr-1.5"></i> Device Registration Parameters
                        </h5>
                    </div>
                    <form action="{{ route('biometric.devices.store') }}" method="POST">
                        @csrf
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <!-- Device Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Main Gate Terminal #01" value="{{ old('name') }}" required>
                                </div>

                                <!-- Serial Number -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Serial Number</label>
                                    <input type="text" name="serial_number" class="form-control" placeholder="e.g. GED7253700485" value="{{ old('serial_number') }}">
                                </div>

                                <!-- Brand / Protocol -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Device Brand &amp; Protocol <span class="text-danger">*</span></label>
                                    <select name="brand" class="form-control" required id="device_brand">
                                        <option value="">— Select your device brand —</option>
                                        <option value="ZKTeco — ADMS / iclock" {{ old('brand') == 'ZKTeco — ADMS / iclock' ? 'selected' : '' }}>ZKTeco — ADMS / iclock</option>
                                        <option value="eSSL — ADMS / iclock" {{ old('brand') == 'eSSL — ADMS / iclock' ? 'selected' : '' }}>eSSL — ADMS / iclock</option>
                                        <option value="Realtime — ADMS / iclock" {{ old('brand') == 'Realtime — ADMS / iclock' ? 'selected' : '' }}>Realtime — ADMS / iclock</option>
                                        <option value="TimeWatch — JSON webhook" {{ old('brand') == 'TimeWatch — JSON webhook' ? 'selected' : '' }}>TimeWatch — JSON webhook</option>
                                        <option value="Mantra — JSON webhook" {{ old('brand') == 'Mantra — JSON webhook' ? 'selected' : '' }}>Mantra — JSON webhook</option>
                                        <option value="Hikvision — JSON webhook" {{ old('brand') == 'Hikvision — JSON webhook' ? 'selected' : '' }}>Hikvision — JSON webhook</option>
                                        <option value="Other / Generic — JSON webhook" {{ old('brand') == 'Other / Generic — JSON webhook' ? 'selected' : '' }}>Other / Generic — JSON webhook</option>
                                    </select>
                                </div>

                                <!-- Push Mode -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Integration / Push Mode <span class="text-danger">*</span></label>
                                    <select name="push_mode" class="form-control" required id="push_mode">
                                        <option value="agent" {{ old('push_mode', 'agent') == 'agent' ? 'selected' : '' }}>Agent Sync (Local ZK Agent Daemon)</option>
                                        <option value="cloud_push" {{ old('push_mode') == 'cloud_push' ? 'selected' : '' }}>Cloud Push (Direct ADMS / Webhook)</option>
                                        <option value="camera" {{ old('push_mode') == 'camera' ? 'selected' : '' }}>IP Camera AI (RTSP Video Stream)</option>
                                    </select>
                                </div>

                                <!-- IP Address -->
                                <div class="col-md-8 mb-3">
                                    <label class="font-weight-bold small text-dark">IP Address / Host <span class="text-danger">*</span></label>
                                    <input type="text" name="ip_address" class="form-control font-monospace" placeholder="e.g. 192.168.1.201" value="{{ old('ip_address', '192.168.1.201') }}" required>
                                    <small class="text-muted">Local IP on the school LAN (for Agent) or public IP / RTSP stream address.</small>
                                </div>

                                <!-- Port -->
                                <div class="col-md-4 mb-3">
                                    <label class="font-weight-bold small text-dark">Port <span class="text-danger">*</span></label>
                                    <input type="number" name="port" class="form-control font-monospace" value="{{ old('port', 4370) }}" required>
                                    <small class="text-muted">ZKTeco default is 4370.</small>
                                </div>

                                <!-- Direction -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Attendance Direction</label>
                                    <select name="direction" class="form-control">
                                        <option value="auto">Auto (Depends on device punch status key)</option>
                                        <option value="in">Check-IN Only</option>
                                        <option value="out">Check-OUT Only</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Operational Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active">Active (Processing &amp; Syncing Logs)</option>
                                        <option value="inactive">Inactive (Disabled)</option>
                                    </select>
                                </div>

                                <!-- Feature Checkboxes -->
                                <div class="col-12 mt-2 pt-3 border-top">
                                    <label class="font-weight-bold small text-dark d-block mb-2">Module Integrations</label>
                                    <div class="custom-control custom-checkbox custom-control-inline mr-4">
                                        <input type="checkbox" class="custom-control-input" id="is_attendance" name="is_attendance" value="1" checked>
                                        <label class="custom-control-label font-weight-normal" for="is_attendance">
                                            <strong>Attendance Logging</strong> &mdash; record punches into student &amp; staff attendance ledgers.
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="is_monitoring" name="is_monitoring" value="1">
                                        <label class="custom-control-label font-weight-normal" for="is_monitoring">
                                            <strong>Face Monitoring AI</strong> &mdash; log facial detection snapshots into review feed.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top py-3 d-flex justify-content-end" style="gap: 10px;">
                            <a href="{{ route('biometric.devices') }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Save &amp; Register Device
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


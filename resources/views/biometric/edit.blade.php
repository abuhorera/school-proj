@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Edit Biometric Device &mdash; {{ $device->name }}</h3>
                <p class="text-muted small mb-0">Modify network parameters, brand protocol, or operational mode.</p>
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
                            <i class="fas fa-pen-nib text-primary mr-1.5"></i> Update Device Configuration
                        </h5>
                    </div>
                    <form action="{{ route('biometric.devices.update', $device->id) }}" method="POST">
                        @csrf
                        @method('PUT')
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
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $device->name) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Serial Number</label>
                                    <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $device->serial_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Device Brand &amp; Protocol <span class="text-danger">*</span></label>
                                    <select name="brand" class="form-control" required>
                                        <option value="ZKTeco — ADMS / iclock" {{ $device->brand == 'ZKTeco — ADMS / iclock' ? 'selected' : '' }}>ZKTeco — ADMS / iclock</option>
                                        <option value="eSSL — ADMS / iclock" {{ $device->brand == 'eSSL — ADMS / iclock' ? 'selected' : '' }}>eSSL — ADMS / iclock</option>
                                        <option value="Realtime — ADMS / iclock" {{ $device->brand == 'Realtime — ADMS / iclock' ? 'selected' : '' }}>Realtime — ADMS / iclock</option>
                                        <option value="TimeWatch — JSON webhook" {{ $device->brand == 'TimeWatch — JSON webhook' ? 'selected' : '' }}>TimeWatch — JSON webhook</option>
                                        <option value="Mantra — JSON webhook" {{ $device->brand == 'Mantra — JSON webhook' ? 'selected' : '' }}>Mantra — JSON webhook</option>
                                        <option value="Hikvision — JSON webhook" {{ $device->brand == 'Hikvision — JSON webhook' ? 'selected' : '' }}>Hikvision — JSON webhook</option>
                                        <option value="Other / Generic — JSON webhook" {{ $device->brand == 'Other / Generic — JSON webhook' ? 'selected' : '' }}>Other / Generic — JSON webhook</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Integration / Push Mode <span class="text-danger">*</span></label>
                                    <select name="push_mode" class="form-control" required>
                                        <option value="agent" {{ $device->push_mode == 'agent' ? 'selected' : '' }}>Agent Sync (Local ZK Agent Daemon)</option>
                                        <option value="cloud_push" {{ $device->push_mode == 'cloud_push' ? 'selected' : '' }}>Cloud Push (Direct ADMS / Webhook)</option>
                                        <option value="camera" {{ $device->push_mode == 'camera' ? 'selected' : '' }}>IP Camera AI (RTSP Video Stream)</option>
                                    </select>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="font-weight-bold small text-dark">IP Address / Host <span class="text-danger">*</span></label>
                                    <input type="text" name="ip_address" class="form-control font-monospace" value="{{ old('ip_address', $device->ip_address) }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="font-weight-bold small text-dark">Port <span class="text-danger">*</span></label>
                                    <input type="number" name="port" class="form-control font-monospace" value="{{ old('port', $device->port) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Attendance Direction</label>
                                    <select name="direction" class="form-control">
                                        <option value="auto" {{ $device->direction == 'auto' ? 'selected' : '' }}>Auto (Depends on device punch status key)</option>
                                        <option value="in" {{ $device->direction == 'in' ? 'selected' : '' }}>Check-IN Only</option>
                                        <option value="out" {{ $device->direction == 'out' ? 'selected' : '' }}>Check-OUT Only</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Operational Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $device->status == 'active' ? 'selected' : '' }}>Active (Processing &amp; Syncing Logs)</option>
                                        <option value="inactive" {{ $device->status == 'inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                                    </select>
                                </div>

                                <div class="col-12 mt-2 pt-3 border-top">
                                    <label class="font-weight-bold small text-dark d-block mb-2">Module Integrations</label>
                                    <div class="custom-control custom-checkbox custom-control-inline mr-4">
                                        <input type="checkbox" class="custom-control-input" id="is_attendance" name="is_attendance" value="1" {{ $device->is_attendance ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal" for="is_attendance">
                                            <strong>Attendance Logging</strong>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="is_monitoring" name="is_monitoring" value="1" {{ $device->is_monitoring ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal" for="is_monitoring">
                                            <strong>Face Monitoring AI</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top py-3 d-flex justify-content-end" style="gap: 10px;">
                            <a href="{{ route('biometric.devices') }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Update Device
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


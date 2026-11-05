@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="qr-title-area">
                <h3 class="font-weight-bold text-dark mb-0">QR Code Attendance — Settings</h3>
                <p class="text-muted small mb-0">Configure scan terminal policies, allowed barcode types, and auto-punch rules.</p>
            </div>
            <div>
                <a class="btn btn-qr-primary" href="{{ route('qr-attendance.scanner') }}">
                    <i class="fas fa-camera mr-1"></i> Open Scan Station
                </a>
            </div>
        </div>

        @include('qr_attendance.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Settings Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-sliders-h text-primary mr-1"></i> Scan Station Configuration
                        </h5>
                    </div>
                    <form action="{{ route('qr-attendance.settings.save') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <!-- Auto Attendance Switch -->
                            <div class="form-group mb-4 pb-3 border-bottom">
                                <label class="font-weight-bold d-block">Auto Attendance</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="auto_attendance" name="auto_attendance" value="1" {{ $settings->auto_attendance ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-normal text-muted" for="auto_attendance">
                                        Submit attendance automatically on a successful scan without requiring operator confirmation.
                                    </label>
                                </div>
                            </div>

                            <!-- Machine Types -->
                            @php
                                $machines = is_array($settings->machine_types) ? $settings->machine_types : (is_string($settings->machine_types) ? (json_decode($settings->machine_types, true) ?: [$settings->machine_types]) : ['camera', 'barcode_gun']);
                            @endphp
                            <div class="form-group mb-4 pb-3 border-bottom">
                                <label class="font-weight-bold d-block">Input Devices / Machine Types</label>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="m_camera" name="machine_types[]" value="camera" {{ in_array('camera', $machines) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-normal" for="m_camera">
                                        <strong>Camera Scanner</strong> &mdash; opens browser camera (webcam, tablet, or phone camera).
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="m_gun" name="machine_types[]" value="barcode_gun" {{ in_array('barcode_gun', $machines) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-normal" for="m_gun">
                                        <strong>Barcode Gun / USB Reader</strong> &mdash; accepts keystrokes directly from hardware scanner guns.
                                    </label>
                                </div>
                            </div>

                            <!-- Camera Facing -->
                            <div class="form-group mb-4 pb-3 border-bottom">
                                <label class="font-weight-bold d-block">Camera Facing Direction</label>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" class="custom-control-input" id="face_env" name="camera_facing" value="environment" {{ $settings->camera_facing == 'environment' ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-normal" for="face_env">
                                        <strong>Back Camera (Environment)</strong> &mdash; default for handheld phones or external webcams pointing outwards.
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="face_user" name="camera_facing" value="user" {{ $settings->camera_facing == 'user' ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-normal" for="face_user">
                                        <strong>Front Camera (User)</strong> &mdash; for wall-mounted tablet kiosks facing students.
                                    </label>
                                </div>
                            </div>

                            <!-- Allowed Formats -->
                            @php
                                $formats = is_array($settings->allowed_formats) ? $settings->allowed_formats : (is_string($settings->allowed_formats) ? (json_decode($settings->allowed_formats, true) ?: [$settings->allowed_formats]) : ['QR_CODE', 'CODE_128', 'EAN_13']);
                            @endphp
                            <div class="form-group mb-4 pb-3 border-bottom">
                                <label class="font-weight-bold d-block">Allowed Barcode &amp; QR Formats</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="fmt_qr" name="allowed_formats[]" value="QR_CODE" {{ in_array('QR_CODE', $formats) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="fmt_qr"><strong>QR_CODE</strong> (2D Matrix ID Cards)</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="fmt_c128" name="allowed_formats[]" value="CODE_128" {{ in_array('CODE_128', $formats) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="fmt_c128"><strong>CODE_128</strong> (Linear Barcodes)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="fmt_ean" name="allowed_formats[]" value="EAN_13" {{ in_array('EAN_13', $formats) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="fmt_ean"><strong>EAN_13</strong> (International Barcodes)</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="fmt_upc" name="allowed_formats[]" value="UPC_A" {{ in_array('UPC_A', $formats) ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal" for="fmt_upc"><strong>UPC_A</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cutoff Times -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Late Arrival Cutoff Time</label>
                                    <input type="time" name="late_cutoff_time" class="form-control" value="{{ $settings->late_cutoff_time }}">
                                    <small class="text-muted">Arrivals after this time are automatically marked <strong>Late</strong>.</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Half Day Cutoff Time</label>
                                    <input type="time" name="half_day_cutoff_time" class="form-control" value="{{ $settings->half_day_cutoff_time }}">
                                    <small class="text-muted">Early departures before this time are flagged <strong>Half Day</strong>.</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white text-right">
                            <a href="{{ route('qr-attendance.scanner') }}" class="btn btn-default mr-2">Open Scan Station</a>
                            <button type="submit" class="btn btn-qr-primary">
                                <i class="fas fa-save mr-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: How it Works Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-info-circle text-info mr-1"></i> How it works
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <strong class="text-dark d-block mb-1"><i class="fas fa-camera text-primary mr-1"></i> Camera Device</strong>
                            <p class="text-muted small mb-0">
                                Opens your webcam or tablet camera and decodes QR/barcode in real time. Requires HTTPS in production.
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong class="text-dark d-block mb-1"><i class="fas fa-barcode text-success mr-1"></i> Barcode Gun</strong>
                            <p class="text-muted small mb-0">
                                Plug in any USB or Bluetooth laser scanner. It behaves as a rapid keyboard wedge, pressing Enter immediately after each card scan.
                            </p>
                        </div>
                        <div class="mb-0">
                            <strong class="text-dark d-block mb-1"><i class="fas fa-bolt text-warning mr-1"></i> De-bounce &amp; Cooldown</strong>
                            <p class="text-muted small mb-0">
                                A card held continuously in front of the lens triggers a 3-second lockout cooldown to prevent duplicate duplicate triggers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

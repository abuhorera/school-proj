@extends('layouts.app')

@section('title', 'Apps Center')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-th text-primary mr-2"></i> Apps Center</h3>
            <p class="text-muted mb-0 small">Connect add-on tools, payment gateways, WhatsApp messaging, and hardware terminal drivers.</p>
        </div>
        <div>
            <span class="badge badge-success px-3 py-2 font-weight-bold">
                <i class="fas fa-check-circle mr-1"></i> Multi-School Cloud Connected
            </span>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-cubes fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">12</div>
                        <small class="text-muted">Connected Apps</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-shield-alt fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">100%</div>
                        <small class="text-muted">API Health Status</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-plug fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">3</div>
                        <small class="text-muted">Active Webhooks</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-sync fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">Live</div>
                        <small class="text-muted">Auto Sync Enabled</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- App Marketplace Grid --}}
    <div class="row">
        @php
            $apps = [
                ['name' => 'Razorpay Payment Gateway', 'cat' => 'Payments', 'icon' => 'fa-credit-card', 'color' => 'primary', 'desc' => 'Accept automated UPI, debit cards, and netbanking fee payments with instant reconciliation.', 'status' => 'Active'],
                ['name' => 'WhatsApp Business Cloud API', 'cat' => 'Communication', 'icon' => 'fab fa-whatsapp', 'color' => 'success', 'desc' => 'Send real-time attendance alerts, fee reminders, and exam marks cards directly to parents.', 'status' => 'Active'],
                ['name' => 'Zoom Meetings for Education', 'cat' => 'Virtual LMS', 'icon' => 'fa-video', 'color' => 'info', 'desc' => 'Auto-generate virtual meeting rooms and sync attendance with academic timetable periods.', 'status' => 'Active'],
                ['name' => 'Biometric ADMS Push Agent', 'cat' => 'Hardware', 'icon' => 'fa-fingerprint', 'color' => 'dark', 'desc' => 'Direct TCP/HTTP push driver syncing ZKTeco, eSSL, and Realtime biometric punch terminals.', 'status' => 'Active'],
                ['name' => 'Google Meet & Classroom', 'cat' => 'Virtual LMS', 'icon' => 'fab fa-google', 'color' => 'danger', 'desc' => 'Google Workspace single-sign-on and classroom assignment sync for teachers and students.', 'status' => 'Active'],
                ['name' => 'AWS S3 Cloud Vault Storage', 'cat' => 'Infrastructure', 'icon' => 'fab fa-aws', 'color' => 'warning', 'desc' => 'Encrypted cloud document vault storing student records, birth certificates, and TC scans.', 'status' => 'Active'],
                ['name' => 'GPS Fleet Telematics Engine', 'cat' => 'Transport', 'icon' => 'fa-bus', 'color' => 'secondary', 'desc' => 'Live vehicle speed, geo-fencing boundary alerts, and real-time parent tracking maps.', 'status' => 'Active'],
                ['name' => 'TallyPrime Accounting Bridge', 'cat' => 'Finance', 'icon' => 'fa-file-invoice-dollar', 'color' => 'primary', 'desc' => 'Export daily fee receipts and voucher journals into Tally XML format seamlessly.', 'status' => 'Ready to Connect'],
            ];
        @endphp

        @foreach($apps as $app)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 mr-3 bg-light text-{{ $app['color'] }}">
                                <i class="{{ $app['icon'] }} fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">{{ $app['name'] }}</h6>
                                <span class="badge badge-light border text-muted small">{{ $app['cat'] }}</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">{{ $app['desc'] }}</p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            @if($app['status'] == 'Active')
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                <button type="button" class="btn btn-outline-primary btn-sm px-3">Configure</button>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Available</span>
                                <button type="button" class="btn btn-primary btn-sm px-3">Connect</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection


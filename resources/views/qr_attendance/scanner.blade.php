@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="qr-title-area">
                <h3 class="font-weight-bold text-dark mb-0">QR Code Attendance</h3>
                <p class="text-muted small mb-0">Scan an ID card to mark the IN and OUT time for students and staff.</p>
            </div>
            <div>
                <a class="btn btn-outline-secondary" href="{{ route('qr-attendance.settings') }}">
                    <i class="fas fa-cog mr-1"></i> Settings
                </a>
            </div>
        </div>

        @include('qr_attendance.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column: Camera Viewfinder & Barcode Reader -->
            <div class="col-lg-7 mb-3">
                <div class="card shadow-sm h-100 border-0" style="border-radius: 8px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-camera text-primary mr-2"></i> Scan Your ID Card QR Code / Barcode
                        </h6>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="autoSubmitToggle" {{ $settings->auto_attendance ? 'checked' : '' }}>
                            <label class="custom-control-label small font-weight-bold text-muted" for="autoSubmitToggle">Auto submit on</label>
                        </div>
                    </div>

                    <div class="card-body p-4 text-center">
                        <!-- Camera Viewfinder Stage Simulation -->
                        <div id="qr-stage-wrap" class="position-relative mx-auto rounded overflow-hidden shadow-sm mb-3" style="max-width: 440px; height: 280px; background: #0f172a; border: 2px solid #334155;">
                            <!-- Animated Laser Scan Line -->
                            <div class="qr-laser-line"></div>

                            <!-- Target Reticle Box -->
                            <div class="qr-reticle"></div>

                            <!-- Simulated Camera Video/Viewfinder -->
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white p-3">
                                <i class="fas fa-qrcode fa-4x text-light opacity-50 mb-2"></i>
                                <span class="badge badge-success px-2 py-1 mb-1"><i class="fas fa-video mr-1"></i> Camera Scanner Active</span>
                                <small class="text-muted text-center" style="font-size: 0.75rem;">
                                    Point student or staff ID card towards camera lens or use USB barcode gun.
                                </small>
                            </div>
                        </div>

                        <!-- USB Gun / Keyboard Wedge Manual Code Input -->
                        <div class="input-group input-group-lg max-w-md mx-auto mb-3" style="max-width: 440px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-barcode text-primary"></i></span>
                            </div>
                            <input type="text" id="manualBarcodeInput" class="form-control" placeholder="Scan or enter ID card code..." autofocus autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-osm-primary font-weight-bold px-3" type="button" id="btnManualSubmit">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Test Scan Chips -->
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-info m-1 test-code-btn" data-code="YIS-STD-1">
                                <i class="fas fa-user-graduate mr-1"></i> Scan Kabir Singh (Student)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary m-1 test-code-btn" data-code="YIS-STF-1">
                                <i class="fas fa-user-tie mr-1"></i> Scan Dr. Arvind Mehta (Staff)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger m-1 test-code-btn" data-code="INVALID-QR-999">
                                <i class="fas fa-times-circle mr-1"></i> Scan Unknown QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Result Profile Card -->
            <div class="col-lg-5 mb-3">
                <div class="card shadow-sm h-100 border-0 qr-result-card" id="profile_card" style="border-radius: 8px;">
                    <div class="card-header bg-white py-3">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-id-card text-success mr-2"></i> Card Holder Details
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex align-items-center justify-content-center" id="profile_data">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-id-badge fa-4x text-light mb-3"></i>
                            <h5 class="text-dark font-weight-bold">Scan an ID card to begin</h5>
                            <p class="small text-muted mb-0">The card holder’s details, photo, and attendance status appear here once a code is read.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Stats Row -->
        <div class="card shadow-sm border-0 mt-2 mb-4" style="border-radius: 8px;">
            <div class="card-body p-3">
                <div class="row text-center align-items-center">
                    <div class="col-md-2 border-right mb-2 mb-md-0">
                        <div class="h4 font-weight-bold text-success mb-0" id="cntMarkedIn">{{ $counts['marked_in'] }}</div>
                        <small class="text-muted font-weight-bold">marked IN</small>
                    </div>

                    <div class="col-md-2 border-right mb-2 mb-md-0">
                        <div class="h4 font-weight-bold text-primary mb-0" id="cntMarkedOut">{{ $counts['marked_out'] }}</div>
                        <small class="text-muted font-weight-bold">marked OUT</small>
                    </div>

                    <div class="col-md-2 border-right mb-2 mb-md-0">
                        <div class="h4 font-weight-bold text-info mb-0" id="cntAlreadyDone">{{ $counts['already_done'] }}</div>
                        <small class="text-muted font-weight-bold">already done</small>
                    </div>

                    <div class="col-md-2 border-right mb-2 mb-md-0">
                        <div class="h4 font-weight-bold text-danger mb-0" id="cntNotRecognised">{{ $counts['not_recognised'] }}</div>
                        <small class="text-muted font-weight-bold">not recognised</small>
                    </div>

                    <div class="col-md-2 border-right mb-2 mb-md-0">
                        <div class="h4 font-weight-bold text-warning mb-0" id="cntNotConfirmed">{{ $counts['not_confirmed'] }}</div>
                        <small class="text-muted font-weight-bold">not confirmed</small>
                    </div>

                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetLiveCounters()">
                            <i class="fas fa-redo-alt mr-1"></i> Reset count
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .qr-laser-line {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: #ef4444;
        box-shadow: 0 0 8px #ef4444, 0 0 14px #ef4444;
        animation: qrLaserScan 2.5s infinite alternate ease-in-out;
        z-index: 5;
    }
    @keyframes qrLaserScan {
        0% { top: 15%; opacity: 0.8; }
        100% { top: 85%; opacity: 0.8; }
    }
    .qr-reticle {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 180px; height: 180px;
        border: 2px dashed rgba(255,255,255,0.4);
        border-radius: 8px;
        z-index: 4;
        pointer-events: none;
    }
</style>

<script>
    window.qrCfg = {
        resolveUrl: "{{ route('qr-attendance.resolve') }}",
        punchUrl:   "{{ route('qr-attendance.punch') }}",
        statusUrl:  "{{ route('qr-attendance.status') }}",
        csrf:       "{{ csrf_token() }}",
        auto:       {{ $settings->auto_attendance ? 'true' : 'false' }}
    };

    const input = document.getElementById('manualBarcodeInput');
    const profile = document.getElementById('profile_data');
    const btnSubmit = document.getElementById('btnManualSubmit');
    const autoToggle = document.getElementById('autoSubmitToggle');

    autoToggle.addEventListener('change', function() {
        window.qrCfg.auto = this.checked;
    });

    // Audio Chime via Web Audio API
    function playBeep(success = true) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = success ? 'sine' : 'sawtooth';
            osc.frequency.setValueAtTime(success ? 880 : 220, ctx.currentTime);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            osc.start();
            osc.stop(ctx.currentTime + (success ? 0.2 : 0.4));
        } catch(e) {}
    }

    function processCode(code) {
        if (!code || !code.trim()) return;
        code = code.trim();

        profile.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i><p class="text-muted font-weight-bold">Resolving ID Card…</p></div>';

        fetch(window.qrCfg.resolveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.qrCfg.csrf
            },
            body: JSON.stringify({ code: code })
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 1) {
                profile.innerHTML = res.profile_html;
                if (res.already_complete) {
                    playBeep(false);
                    document.getElementById('cntAlreadyDone').innerText = parseInt(document.getElementById('cntAlreadyDone').innerText) + 1;
                } else if (res.punched) {
                    playBeep(true);
                    if (res.punched.action === 'in') {
                        document.getElementById('cntMarkedIn').innerText = parseInt(document.getElementById('cntMarkedIn').innerText) + 1;
                    } else {
                        document.getElementById('cntMarkedOut').innerText = parseInt(document.getElementById('cntMarkedOut').innerText) + 1;
                    }
                } else {
                    document.getElementById('cntNotConfirmed').innerText = parseInt(document.getElementById('cntNotConfirmed').innerText) + 1;
                }
            } else {
                playBeep(false);
                profile.innerHTML = `<div class="text-center py-5"><div class="badge badge-danger p-3 mb-3 rounded-circle"><i class="fas fa-times fa-2x"></i></div><h5 class="text-danger font-weight-bold">Scan Rejected</h5><p class="text-muted small">${res.msg}</p></div>`;
                document.getElementById('cntNotRecognised').innerText = parseInt(document.getElementById('cntNotRecognised').innerText) + 1;
            }
        })
        .catch(err => {
            playBeep(false);
            profile.innerHTML = `<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-2"></i><p>Network / Server error.</p></div>`;
        });

        input.value = '';
        input.focus();
    }

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            processCode(input.value);
        }
    });

    btnSubmit.addEventListener('click', function() {
        processCode(input.value);
    });

    document.querySelectorAll('.test-code-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            processCode(this.dataset.code);
        });
    });

    // Delegate Confirm Punch
    profile.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-confirm-punch');
        if (btn) {
            const status = (profile.querySelector('input[name="qr_status"]:checked') || {}).value || 'Present';
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving…';
            btn.disabled = true;

            fetch(window.qrCfg.punchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.qrCfg.csrf
                },
                body: JSON.stringify({
                    type: btn.dataset.type,
                    id: btn.dataset.id,
                    status: status
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 1) {
                    playBeep(true);
                    btn.outerHTML = `<div class="alert alert-success py-2 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> ${res.msg}</div>`;
                    if (res.action === 'in') {
                        document.getElementById('cntMarkedIn').innerText = parseInt(document.getElementById('cntMarkedIn').innerText) + 1;
                    } else {
                        document.getElementById('cntMarkedOut').innerText = parseInt(document.getElementById('cntMarkedOut').innerText) + 1;
                    }
                } else {
                    playBeep(false);
                    btn.outerHTML = `<div class="alert alert-danger py-2">${res.msg}</div>`;
                }
            });
        }
    });

    function resetLiveCounters() {
        document.getElementById('cntMarkedIn').innerText = '0';
        document.getElementById('cntMarkedOut').innerText = '0';
        document.getElementById('cntAlreadyDone').innerText = '0';
        document.getElementById('cntNotRecognised').innerText = '0';
        document.getElementById('cntNotConfirmed').innerText = '0';
    }
</script>
@endsection


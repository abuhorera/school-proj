@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Biometric API Playground</h3>
                <p class="text-muted small mb-0">Simulate hardware biometric punches, test Cloud Push webhooks, and inspect raw transaction payloads.</p>
            </div>
            <div>
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
        <div class="row">
            <!-- Left: Simulator Form -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-play text-warning mr-1.5"></i> Simulate Hardware Biometric Punch
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="simulatePushForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Target Biometric Machine <span class="text-danger">*</span></label>
                                <select name="device_id" id="sim_device_id" class="form-control" required>
                                    @foreach($devices as $dev)
                                        <option value="{{ $dev->id }}">{{ $dev->name }} ({{ $dev->brand }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Person Type</label>
                                <select name="person_type" id="sim_person_type" class="form-control" onchange="togglePersonDropdown(this.value)">
                                    <option value="student">Student</option>
                                    <option value="staff">Staff Member</option>
                                </select>
                            </div>

                            <div class="form-group mb-3" id="student_select_group">
                                <label class="small font-weight-bold text-dark">Student Candidate</label>
                                <select name="person_id" id="sim_student_id" class="form-control">
                                    @foreach($students as $st)
                                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_number ?? 'ADM-' . $st->id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3 d-none" id="staff_select_group">
                                <label class="small font-weight-bold text-dark">Staff Candidate</label>
                                <select name="staff_id" id="sim_staff_id" class="form-control">
                                    @foreach($staff as $stf)
                                        <option value="{{ $stf->id }}">{{ $stf->first_name }} {{ $stf->last_name }} ({{ $stf->staff_id ?? 'STF-' . $stf->id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Punch Direction</label>
                                    <select name="direction" id="sim_direction" class="form-control">
                                        <option value="Check-IN">Check-IN</option>
                                        <option value="Check-OUT">Check-OUT</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Biometric Method</label>
                                    <select name="method" id="sim_method" class="form-control">
                                        <option value="Fingerprint">Fingerprint</option>
                                        <option value="Face">Facial Recognition</option>
                                        <option value="RFID Card">RFID Card</option>
                                        <option value="Password">PIN / Password</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-warning btn-block font-weight-bold py-2.5 mt-2" onclick="submitSimulatedPunch()">
                                <i class="fas fa-paper-plane mr-1.5"></i> Transmit Simulated Punch
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Webhook Documentation & Payload Response -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #0f172a; color: #fff;">
                    <div class="card-header border-bottom border-slate-800 py-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                        <h5 class="card-title font-weight-bold text-cyan-400 mb-0 font-monospace" style="font-size: 14px;">
                            <i class="fas fa-code mr-1.5"></i> Live Dispatch Output Console
                        </h5>
                        <span class="badge badge-secondary font-monospace" id="statusBadge">READY</span>
                    </div>
                    <div class="card-body p-4 font-monospace small">
                        <p class="text-slate-400 mb-2">Simulate real-time transactions and verify automatic synchronization with core attendance ledgers:</p>
                        <pre id="outputConsole" class="bg-black p-3 rounded text-emerald-400 font-monospace border border-slate-800" style="min-height: 220px; max-height: 300px; overflow-y: auto;">
// Awaiting punch transmission...
// Click "Transmit Simulated Punch" on the left to test.
                        </pre>

                        <div class="mt-3 pt-3 border-top border-slate-800 small text-slate-400">
                            <strong class="text-white d-block mb-1">Direct Cloud Server Endpoint URL:</strong>
                            <code class="text-cyan-300">POST http://127.0.0.1:8000/school/biometric/playground/push</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePersonDropdown(type) {
    if (type === 'staff') {
        document.getElementById('student_select_group').classList.add('d-none');
        document.getElementById('staff_select_group').classList.remove('d-none');
    } else {
        document.getElementById('student_select_group').classList.remove('d-none');
        document.getElementById('staff_select_group').classList.add('d-none');
    }
}

function submitSimulatedPunch() {
    const personType = document.getElementById('sim_person_type').value;
    const personId = personType === 'staff' 
        ? document.getElementById('sim_staff_id').value 
        : document.getElementById('sim_student_id').value;

    const payload = {
        _token: '{{ csrf_token() }}',
        device_id: document.getElementById('sim_device_id').value,
        person_type: personType,
        person_id: personId,
        direction: document.getElementById('sim_direction').value,
        method: document.getElementById('sim_method').value,
    };

    document.getElementById('statusBadge').className = 'badge badge-warning font-monospace';
    document.getElementById('statusBadge').innerText = 'TRANSMITTING...';

    fetch('{{ route("biometric.playground.push") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('statusBadge').className = 'badge badge-success font-monospace';
        document.getElementById('statusBadge').innerText = 'HTTP 200 OK';
        document.getElementById('outputConsole').innerText = JSON.stringify(data, null, 2);
    })
    .catch(err => {
        document.getElementById('statusBadge').className = 'badge badge-danger font-monospace';
        document.getElementById('statusBadge').innerText = 'ERROR';
        document.getElementById('outputConsole').innerText = '// Error: ' + err;
    });
}
</script>
@endsection


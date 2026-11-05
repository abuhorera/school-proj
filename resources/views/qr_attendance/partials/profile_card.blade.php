<div class="qr-profile-card p-3 text-center">
    <!-- Avatar / Crest -->
    <div class="mb-3">
        <div class="d-inline-flex align-items-center justify-content-center text-white font-weight-bold rounded-circle shadow-sm" style="width: 80px; height: 80px; background: linear-gradient(135deg, #0284c7, #4f46e5); font-size: 1.8rem;">
            {{ strtoupper(substr($name, 0, 1)) }}
        </div>
    </div>

    <!-- Candidate Biodata -->
    <h4 class="font-weight-bold text-dark mb-1">{{ $name }}</h4>
    <div class="mb-2">
        <span class="badge {{ $personType === 'staff' ? 'badge-primary' : 'badge-info' }} px-2 py-1">
            {{ strtoupper($personType) }}
        </span>
        <span class="badge badge-light border ml-1">{{ $identifier }}</span>
    </div>

    <div class="text-muted small mb-3">
        <strong>{{ $subtext }}</strong>
    </div>

    <hr class="my-3">

    <!-- Punch Status & Confirmation -->
    @if($alreadyComplete)
        <div class="alert alert-info py-2 px-3 mb-0">
            <i class="fas fa-check-double mr-1"></i>
            <strong>Attendance complete for today:</strong><br>
            <span class="small">IN: <strong>{{ $scan->in_time }}</strong> &middot; OUT: <strong>{{ $scan->out_time }}</strong> ({{ $scan->duration_minutes }}m)</span>
        </div>
    @elseif($auto)
        <div class="alert alert-success py-2 px-3 mb-0 qr-auto-submit">
            <i class="fas {{ $punchAction === 'out' ? 'fa-sign-out-alt' : 'fa-sign-in-alt' }} mr-1"></i>
            <strong>{{ $punchAction === 'out' ? 'Marked OUT' : 'Marked IN' }} Successfully:</strong><br>
            <span class="h5 font-weight-bold mb-0">{{ $currentTime }}</span>
        </div>
    @else
        <!-- Manual Operator Confirmation Mode -->
        <div class="text-left bg-light p-3 rounded border mb-3">
            <label class="font-weight-bold small text-muted mb-2 d-block">SELECT ATTENDANCE STATUS</label>
            <div class="d-flex justify-content-around">
                <div class="custom-control custom-radio">
                    <input type="radio" id="st_present" name="qr_status" value="Present" class="custom-control-input" checked>
                    <label class="custom-control-label text-success font-weight-bold" for="st_present">Present</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="st_late" name="qr_status" value="Late" class="custom-control-input">
                    <label class="custom-control-label text-warning font-weight-bold" for="st_late">Late</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="st_half" name="qr_status" value="Half Day" class="custom-control-input">
                    <label class="custom-control-label text-info font-weight-bold" for="st_half">Half Day</label>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success btn-block font-weight-bold py-2 btn-confirm-punch" data-type="{{ $personType }}" data-id="{{ $personId }}">
            <i class="fas {{ $punchAction === 'out' ? 'fa-sign-out-alt' : 'fa-sign-in-alt' }} mr-1"></i>
            Confirm {{ $punchAction === 'out' ? 'Mark OUT' : 'Mark IN' }} ({{ $currentTime }})
        </button>
    @endif
</div>


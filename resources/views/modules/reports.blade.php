@extends('layouts.app')

@section('title', 'Reports Hub & Analytics')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-chart-pie text-primary mr-2"></i> Reports Hub &amp; Executive Analytics</h3>
            <p class="text-muted mb-0 small">Cross-module intelligence, financial velocity, student academic progress, and institutional audit reports.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" onclick="alert('Exporting Master Institutional Audit Package (Excel & PDF)...')">
                <i class="fas fa-file-export mr-1"></i> Export Master Audit (ZIP)
            </button>
        </div>
    </div>

    {{-- Executive Analytics Grid --}}
    <div class="row">
        @php
            $reportModules = [
                ['title' => 'Fees 360 & Revenue Velocity', 'icon' => 'fa-rupee-sign', 'color' => 'primary', 'desc' => 'Fee collection summaries, outstanding dues aging, concession audits, and mode-wise payment split.', 'url' => route('fees.index')],
                ['title' => 'Student 360 & Retention Analytics', 'icon' => 'fa-user-graduate', 'color' => 'success', 'desc' => 'Enrollment demographics, gender ratio, student attrition risk, and multi-year admission velocity.', 'url' => route('students.index')],
                ['title' => 'Examination & Mark Distribution', 'icon' => 'fa-graduation-cap', 'color' => 'info', 'desc' => 'Subject-wise bell curves, toppers ledger, fail remediation lists, and CBC competency achievement.', 'url' => route('exams.marks.entry')],
                ['title' => 'Attendance Heatmaps & Biometrics', 'icon' => 'fa-user-check', 'color' => 'warning', 'desc' => 'Monthly class attendance rates, staff biometric punch compliance, and habitual absenteeism alerts.', 'url' => route('attendance.index')],
                ['title' => 'HR, Staff & Payroll Disbursement', 'icon' => 'fa-users', 'color' => 'dark', 'desc' => 'Staff salary slips, statutory PF/ESI deduction summaries, teacher workload, and leave usage.', 'url' => route('staff.index')],
                ['title' => 'Fixed Assets & Campus Infrastructure', 'icon' => 'fa-cubes', 'color' => 'secondary', 'desc' => 'Acquisition value vs book value depreciation, asset audits, and equipment repair history.', 'url' => route('modules.assets')],
                ['title' => 'Transport Fleet & Route Efficiency', 'icon' => 'fa-bus', 'color' => 'danger', 'desc' => 'Bus route occupancy percentages, fuel expenses, and driver compliance licensing checklist.', 'url' => route('modules.transport')],
                ['title' => 'Library Circulation & Fines Ledger', 'icon' => 'fa-book', 'color' => 'primary', 'desc' => 'Most borrowed book categories, overdue fine collection, and lost book replacement ledgers.', 'url' => route('modules.library')],
                ['title' => 'Canteen Cashless Wallet Analytics', 'icon' => 'fa-utensils', 'color' => 'success', 'desc' => 'Meal counter sales velocity, contractor revenue settlements, and student wallet balances.', 'url' => route('modules.canteen')],
            ];
        @endphp

        @foreach($reportModules as $rm)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 mr-3 bg-light text-{{ $rm['color'] }}">
                                <i class="fas {{ $rm['icon'] }} fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0">{{ $rm['title'] }}</h6>
                                <span class="badge badge-light border text-muted small">Analytics Report</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">{{ $rm['desc'] }}</p>
                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="small font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i> Live Synced</span>
                            <a href="{{ $rm['url'] }}" class="btn btn-outline-primary btn-sm px-3">Open Hub &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

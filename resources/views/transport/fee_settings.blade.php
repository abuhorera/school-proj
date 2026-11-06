@extends('layouts.app')

@section('title', 'Transport Fee Settings')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('transport.nav')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- Billing Configuration Form -->
        <div class="col-lg-8 mb-4">
            <div class="ts-card">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-amber-500"></i>
                        Billing Configuration
                    </span>
                    <span class="badge {{ $settings->is_active ? 'badge-in-service' : 'badge-maintenance' }}">
                        {{ $settings->is_active ? 'Billing ON' : 'Billing OFF' }}
                    </span>
                </div>
                <div class="p-4">
                    <form action="{{ route('school.transport.fee-settings.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    Transport Fee Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2" name="fee_type_id" required>
                                    <option value="">-- Select Fee Type --</option>
                                    @foreach($feeTypes as $ft)
                                        <option value="{{ $ft->id }}" {{ ($settings->fee_type_id == $ft->id || strtolower($ft->name) == 'transport fee') ? 'selected' : '' }}>
                                            {{ $ft->name }}
                                        </option>
                                    @endforeach
                                    @if($feeTypes->isEmpty())
                                        <option value="1" selected>Transport Fee</option>
                                    @endif
                                </select>
                                <small class="text-muted">Commonly “Transport Fee” or “Bus Fee”.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    Fee Group <span class="text-danger">*</span>
                                </label>
                                <select class="form-control select2" name="fee_group_id">
                                    <option value="">-- Select Fee Group --</option>
                                    @foreach($feeGroups as $fg)
                                        <option value="{{ $fg->id }}" {{ $settings->fee_group_id == $fg->id ? 'selected' : '' }}>
                                            {{ $fg->name }}
                                        </option>
                                    @endforeach
                                    @if($feeGroups->isEmpty())
                                        <option value="1" selected>General Fees</option>
                                    @endif
                                </select>
                                <small class="text-muted">Master grouping under which transport ledger is accounted.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    Billing Months <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" name="billing_months" min="1" max="12" value="{{ $settings->billing_months ?? 12 }}" required>
                                <small class="text-muted">Typically 10, 11, or 12 months.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    Session Start Date
                                </label>
                                <input type="date" class="form-control" name="session_start_date" value="{{ $settings->session_start_date ?? date('Y-04-01') }}">
                                <small class="text-muted">School academic session opening date.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-dark">
                                    Due Day of Month <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" name="due_day_of_month" min="1" max="31" value="{{ $settings->due_day_of_month ?? 10 }}" required>
                                <small class="text-muted">e.g. 10th of every month.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="auto_assign_on_route" name="auto_assign_on_route" value="1" {{ ($settings->auto_assign_on_route ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold text-dark" for="auto_assign_on_route">
                                    Auto-generate transport fee bills when assigning a student to a route
                                </label>
                            </div>
                            <small class="text-muted d-block ml-4 pl-2">When enabled, adding a student to a bus route stop automatically creates recurring monthly fee invoices for all remaining session months.</small>
                        </div>

                        <div class="mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ ($settings->is_active ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold text-dark" for="is_active">
                                    Transport Billing System Active
                                </label>
                            </div>
                            <small class="text-muted d-block ml-4 pl-2">Disable only if your school bills transport externally or includes it inside total composite tuition.</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning font-weight-bold px-4 py-2 shadow-sm text-dark">
                                <i class="fas fa-save mr-1.5"></i> Save Fee Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar / Important notes -->
        <div class="col-lg-4 mb-4">
            <div class="ts-card">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3 text-amber-600 font-weight-bold">
                        <i class="fas fa-shield-alt fa-lg mr-2"></i>
                        <span>Accounting Safety & Policy</span>
                    </div>

                    <div class="bg-amber-50 rounded-lg p-3 border border-amber-200 mb-3">
                        <div class="text-xs uppercase font-bold text-amber-800 mb-1">Active Bus Riders</div>
                        <div class="text-2xl font-black text-amber-950">{{ $activeRidersCount ?? 0 }}</div>
                        <div class="text-xs text-amber-700 mt-1">Students assigned to active routes and stops.</div>
                    </div>

                    <ul class="list-unstyled text-sm text-slate-600 space-y-2 mb-0">
                        <li class="d-flex align-items-start mb-2">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            <span><strong>One fee type:</strong> A single <code>FeeType</code> keeps your balance sheet clean and consolidated.</span>
                        </li>
                        <li class="d-flex align-items-start mb-2">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            <span><strong>Stop-wise pricing:</strong> Individual pickup stop fares are configured on Route management pages.</span>
                        </li>
                        <li class="d-flex align-items-start mb-2">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            <span><strong>Accounting safety:</strong> Stop price changes do not rewrite invoices already settled by parents.</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            <span><strong>Deletion guards:</strong> Routes cannot be deleted while active fee records or riders remain attached.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Explainer / How automatic billing works -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="ts-card border-0 shadow-sm overflow-hidden">
                <div class="bg-slate-900 text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="font-weight-bold mb-1 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-amber-400"></i>
                            How the automatic bus fee works — with examples
                        </h5>
                        <p class="text-slate-300 text-sm mb-0">Transparent billing logic designed to keep parents informed and accounts accurate.</p>
                    </div>
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1.5 rounded-pill">
                        12 Months Dynamic Schedule
                    </span>
                </div>
                <div class="p-4 bg-white">
                    <div class="p-3 bg-slate-50 border rounded-lg mb-4 flex items-center gap-3">
                        <div class="text-3xl">🚌</div>
                        <div>
                            <span class="badge badge-dark text-xs uppercase font-bold px-2 py-0.5">Core Principle</span>
                            <p class="text-slate-700 text-sm font-medium mb-0 mt-1">
                                When you assign a student to a route stop, the ERP <strong>automatically generates fees for all remaining months of the academic year</strong>. The system calculates exact prorated amounts based on when the child joins or stops riding.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded-lg p-3 h-100 bg-emerald-50/40 border-emerald-200">
                                <div class="d-flex align-items-center gap-2 mb-2 text-emerald-800 font-weight-bold">
                                    <i class="fas fa-calendar-check text-emerald-600"></i>
                                    <span>Joined at Session Start</span>
                                </div>
                                <p class="text-xs text-slate-600 mb-2">Student registers in April (Month 1 of academic year).</p>
                                <div class="bg-white p-2.5 rounded border border-emerald-200 font-mono text-xs text-slate-800">
                                    12 months × ₹1,000 = <strong class="text-emerald-700 font-bold">₹12,000/yr</strong>
                                </div>
                                <small class="text-slate-500 d-block mt-2">12 individual monthly installment invoices generated.</small>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="border rounded-lg p-3 h-100 bg-sky-50/40 border-sky-200">
                                <div class="d-flex align-items-center gap-2 mb-2 text-sky-800 font-weight-bold">
                                    <i class="fas fa-user-clock text-sky-600"></i>
                                    <span>Mid-Session Joiner (August)</span>
                                </div>
                                <p class="text-xs text-slate-600 mb-2">Student starts riding midway through session.</p>
                                <div class="bg-white p-2.5 rounded border border-sky-200 font-mono text-xs text-slate-800">
                                    5 remaining mos × ₹1,000 = <strong class="text-sky-700 font-bold">₹5,000</strong>
                                </div>
                                <small class="text-slate-500 d-block mt-2">Prior months are skipped — no unfair retrospective charges.</small>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="border rounded-lg p-3 h-100 bg-amber-50/40 border-amber-200">
                                <div class="d-flex align-items-center gap-2 mb-2 text-amber-800 font-weight-bold">
                                    <i class="fas fa-sign-out-alt text-amber-600"></i>
                                    <span>Mid-Year Cancellation</span>
                                </div>
                                <p class="text-xs text-slate-600 mb-2">Student relocates or stops taking the bus in December.</p>
                                <div class="bg-white p-2.5 rounded border border-amber-200 font-mono text-xs text-slate-800">
                                    Future bills <strong>cancelled</strong>; past used months stay due.
                                </div>
                                <small class="text-slate-500 d-block mt-2">Fair automated reconciliation on student route off-boarding.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


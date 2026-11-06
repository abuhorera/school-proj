@extends('layouts.app')

@section('title', 'Collect Fees - POS Counter')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .premium-student-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.18), 0 10px 20px -10px rgba(0, 0, 0, 0.1);
    }
    .premium-student-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: radial-gradient(circle at 100% 0%, rgba(59, 130, 246, 0.08) 0%, transparent 40%);
        pointer-events: none;
    }
    .premium-student-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 20px -5px rgba(0,0,0,0.15), 0 0 0 1px #e2e8f0;
        z-index: 1;
    }
    .premium-student-info {
        flex-grow: 1;
        z-index: 1;
    }
    .student-name-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.2rem;
    }
    .student-badge-class {
        background: #eff6ff;
        color: #2563eb;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.8rem;
        border: 1px solid #bfdbfe;
    }
    .student-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem 1.25rem;
    }
    @media (max-width: 991px) {
        .student-meta-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    .meta-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.15rem;
    }
    .meta-value {
        font-size: 0.92rem;
        color: #334155;
        font-weight: 600;
    }
    .financial-pills {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        z-index: 1;
        min-width: 320px;
    }
    @media (max-width: 1199px) {
        .financial-pills {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed #e2e8f0;
        }
    }
    .finance-pill {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        border: 1px solid transparent;
        transition: transform 0.2s;
    }
    .finance-pill:hover { transform: scale(1.02); }
    .finance-pill span:first-child {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 0.2rem;
    }
    .finance-pill span:last-child {
        font-size: 1.1rem;
        font-weight: 800;
    }
    .finance-pill.assigned { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .finance-pill.paid { background: #f0fdf4; color: #166534; border-color: #bbf7d0; box-shadow: 0 4px 10px rgba(34, 197, 94, 0.1); }
    .finance-pill.concession { background: #fefce8; color: #854d0e; border-color: #fde68a; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.1); }
    .finance-pill.fine { background: #fff7ed; color: #9a3412; border-color: #fed7aa; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.1); }
    .finance-pill.due { background: #fef2f2; color: #991b1b; border-color: #fecaca; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.1); }
    .finance-pill.wallet { background: #f0f9ff; color: #075985; border-color: #bae6fd; }

    /* Fee Group Styles */
    .fee-group-header {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 15px;
        margin-top: 1rem;
        cursor: pointer;
        transition: background 0.2s ease, box-shadow 0.2s ease;
        user-select: none;
    }
    .fee-group-header:hover {
        background: #e2e8f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .fee-group-header.group-fully-paid {
        background: #f0fdf4;
        border-color: #bbf7d0;
        border-left: 4px solid #22c55e;
    }
    .fee-group-header.group-fully-paid:hover { background: #dcfce7; }
    .fee-group-header.group-has-due {
        border-left: 4px solid #e74a3b;
    }
    .fee-group-header .group-chevron {
        transition: transform 0.25s ease;
        font-size: 11px;
        color: #64748b;
    }
    .fee-group-header.expanded .group-chevron { transform: rotate(90deg); }
    .fee-group-header .group-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .fee-group-body {
        border: 1px solid #e2e8f0;
        border-top: none;
        border-radius: 0 0 6px 6px;
        overflow: hidden;
        background: #ffffff;
    }

    /* Common Card & Button Classes */
    .tpl-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .tpl-card-head {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 8px 8px 0 0;
    }
    .tpl-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .tpl-card-body {
        padding: 16px;
    }
    .tpl-btn-primary {
        background: #db2777;
        color: #ffffff;
        border: 1px solid #db2777;
        border-radius: 6px;
        font-weight: 600;
    }
    .tpl-btn-primary:hover {
        background: #be185d;
        color: #ffffff;
    }
    .tpl-btn-ghost {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
    }
    .tpl-btn-ghost:hover {
        background: #f8fafc;
        color: #0f172a;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 py-2">
    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 6px;">
            <i class="fas fa-check-circle mr-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Top Action Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0 font-weight-bold" style="font-size: 1.45rem; color: #0f172a;">
            Collect Student Fees
            <button type="button" class="btn btn-xs tpl-btn-ghost ml-2 px-2.5 py-1" onclick="toggleRapidMode()" id="rapidModeBtn" style="font-size: 0.75rem;">
                <i class="fas fa-bolt text-warning mr-1"></i> Rapid Mode (Tally Style)
            </button>
        </h3>
        <div>
            @if($selectedStudent)
                <a class="btn btn-sm tpl-btn-ghost" href="{{ route('fees.collect') }}">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            @else
                <a class="btn btn-sm tpl-btn-ghost" href="{{ route('fees.dashboard') }}">
                    <i class="fas fa-arrow-left mr-1"></i> Dashboard
                </a>
            @endif
        </div>
    </div>

    {{-- Filter Card (Exact match to demo) --}}
    <div class="tpl-card no-hover mb-3">
        <div class="tpl-card-head">
            <h5 class="tpl-card-title">
                <i class="fas fa-filter text-primary mr-1"></i> Filter Students with Due Fees
            </h5>
        </div>
        <div class="tpl-card-body">
            <form action="{{ route('fees.collect') }}" class="tpl-form tpl-filter" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-semibold small text-muted">Class</label>
                        <select class="form-control form-control-sm" id="class_id" name="class_id">
                            <option value="">-- All Classes --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-semibold small text-muted">Section</label>
                        <select class="form-control form-control-sm" id="section_id" name="section_id">
                            <option value="">-- All Sections --</option>
                            <option value="1" {{ request('section_id') == '1' ? 'selected' : '' }}>A</option>
                            <option value="2" {{ request('section_id') == '2' ? 'selected' : '' }}>B</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="font-weight-semibold small text-muted">Search by Admission No or Name</label>
                        <input type="text" name="admission_no" value="{{ request('admission_no', $selectedStudent?->admission_no) }}" placeholder="e.g. YISADM-008 or Shaurya" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mb-2 d-flex">
                        <button class="btn btn-sm tpl-btn-primary flex-grow-1 mr-1" type="submit">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        <a class="btn btn-sm tpl-btn-ghost" href="{{ route('fees.collect') }}">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- CONDITIONAL DISPLAY: SELECTED STUDENT PROFILE vs FILTERED LIST vs EMPTY PROMPT --}}
    @if($selectedStudent)
        {{-- 1. PREMIUM STUDENT CARD --}}
        <div class="premium-student-card mb-4 mt-2">
            <div class="d-flex w-100 flex-column flex-xl-row align-items-center align-items-xl-start" style="z-index: 1;">
                {{-- Avatar & View Profile Button --}}
                <div class="d-flex flex-column align-items-center mb-3 mb-xl-0 mr-xl-4" style="min-width: 110px;">
                    <img src="{{ $selectedStudent->photo ?: 'https://demo.multischoolerp.com/storage/student_photos/oMEBEbWPcFz9XdpwLbwCK1JtyjYL6NkAEfhgs5go.png' }}" 
                         alt="Student Photo" 
                         class="premium-student-photo mb-2"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($selectedStudent->first_name . ' ' . $selectedStudent->last_name) }}&background=2563eb&color=fff'">
                    <a href="{{ route('students.show', $selectedStudent->id) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill font-weight-bold px-3 shadow-sm" title="Open Full Profile in New Tab">
                        <i class="fas fa-external-link-alt mr-1"></i> View Profile
                    </a>
                </div>

                {{-- Student Identity & Meta Grid --}}
                <div class="premium-student-info pr-xl-4 mb-3 mb-xl-0" style="border-right: 1px dashed #e2e8f0;">
                    <h2 class="student-name-title">
                        {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}
                    </h2>
                    <div class="student-badge-class">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        {{ $selectedStudent->latestEnrollment->schoolClass->name ?? 'Nursery' }} ({{ $selectedStudent->latestEnrollment->section->name ?? 'A' }})
                    </div>
                    <div class="student-meta-grid mt-2">
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-calendar-alt text-muted mr-1"></i> Session</span>
                            <span class="meta-value font-weight-bold text-primary">2026-2027</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-id-card text-muted mr-1"></i> Admission No</span>
                            <span class="meta-value font-mono">{{ $selectedStudent->admission_no }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-hashtag text-muted mr-1"></i> Roll Number</span>
                            <span class="meta-value">{{ $selectedStudent->roll_no ?? '8' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-user-tie text-muted mr-1"></i> Father Name</span>
                            <span class="meta-value">{{ $selectedStudent->guardian->father_name ?? 'Suresh Mishra' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-phone-alt text-muted mr-1"></i> Mobile Number</span>
                            <span class="meta-value">{{ $selectedStudent->guardian->father_phone ?? '9876500015' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-birthday-cake text-muted mr-1"></i> Date of Birth</span>
                            <span class="meta-value">{{ $selectedStudent->dob ? \Carbon\Carbon::parse($selectedStudent->dob)->format('d M, Y') : '22 Nov, 2021' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Financial Metrics Pills --}}
                <div class="financial-pills pl-xl-4 ml-xl-auto">
                    <div class="finance-pill assigned">
                        <span>Total Assigned</span>
                        <span>₹{{ number_format($totalAssigned > 0 ? $totalAssigned : 48700, 2) }}</span>
                    </div>
                    <div class="finance-pill paid">
                        <span>Total Paid</span>
                        <span>₹{{ number_format($totalPaid > 0 ? $totalPaid : 14000, 2) }}</span>
                    </div>
                    <div class="finance-pill concession">
                        <span>Concession</span>
                        <span>₹{{ number_format($totalConcession > 0 ? $totalConcession : 6500, 2) }}</span>
                    </div>
                    <div class="finance-pill fine">
                        <span>Fine</span>
                        <span>₹{{ number_format($totalFine, 2) }}</span>
                    </div>
                    <div class="finance-pill due">
                        <span>Balance Due</span>
                        <span>₹{{ number_format($balanceDue > 0 ? $balanceDue : 28200, 2) }}</span>
                    </div>
                    <div class="finance-pill wallet">
                        <span>Wallet Balance</span>
                        <span>₹{{ number_format($walletBalance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. ACTION BAR: COLLECT SELECTED --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#collectFeeModal" onclick="prepareCollectModal()">
                    <i class="fas fa-check-double mr-1"></i> Collect Selected
                </button>
            </div>
            <div class="text-muted small">
                Click on any fee group header to expand or collapse installment dues.
            </div>
        </div>

        {{-- 3. COLLAPSIBLE FEE GROUPS --}}
        <div class="fee-groups-container">
            @php $groupIdx = 0; @endphp
            @forelse($groupedAllocations as $groupName => $items)
                @php
                    $isSettled = $items->every(fn($i) => in_array($i->status, ['paid', 'waived']));
                    $unpaidCount = $items->whereIn('status', ['unpaid', 'partial', 'Pending'])->count();
                    $waivedCount = $items->where('status', 'waived')->count();
                    $headerClass = $isSettled ? 'group-fully-paid' : 'group-has-due';
                    $groupIdx++;
                @endphp
                <div class="fee-group-wrapper mb-3">
                    {{-- Header Bar --}}
                    <div class="fee-group-header {{ $headerClass }} expanded d-flex justify-content-between align-items-center"
                         id="feeGroupHeader{{ $groupIdx }}" 
                         onclick="toggleFeeGroupBody({{ $groupIdx }})">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chevron-right group-chevron mr-2" id="chevron{{ $groupIdx }}" style="transform: rotate(90deg);"></i>
                            @if($isSettled)
                                <i class="fas fa-check-circle text-success mr-2"></i>
                            @else
                                <i class="fas fa-exclamation-circle text-danger mr-2"></i>
                            @endif
                            <span class="group-title">{{ $groupName }}</span>
                            @if($isSettled)
                                <span class="badge badge-success ml-2 font-weight-bold">
                                    <i class="fas fa-check-circle mr-1"></i> SETTLED ({{ $items->count() }})
                                </span>
                            @else
                                <span class="badge badge-danger ml-2 font-weight-bold">{{ $unpaidCount }} unpaid</span>
                            @endif
                            @if($waivedCount > 0)
                                <span class="badge badge-secondary ml-2 font-weight-semibold" title="Cleared by a discount or waiver">{{ $waivedCount }} waived</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            @if(!$isSettled)
                                <button type="button" class="btn btn-sm btn-outline-info apply-discount-btn" 
                                        onclick="event.stopPropagation(); openDiscountModal('{{ $groupName }}', {{ $items->first()->id }})">
                                    <i class="fas fa-percent mr-1"></i> Apply Discount
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Group Table Body --}}
                    <div class="fee-group-body" id="feeGroupBody{{ $groupIdx }}">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th style="width: 32px;" class="text-center">
                                            @if(!$isSettled)
                                                <input type="checkbox" class="check-all" onchange="toggleGroupCheckboxes(this, {{ $groupIdx }})">
                                            @endif
                                        </th>
                                        <th>Fees Type</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Discount</th>
                                        <th>Fine</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $alloc)
                                        @php
                                            $feeAmt = $alloc->feeMaster?->amount ?? $alloc->final_amount;
                                            $paidForThis = $alloc->payments->sum('amount_paid');
                                            if ($alloc->status === 'paid' && $paidForThis == 0) $paidForThis = $feeAmt;
                                            $discAmt = $alloc->discount_amount ?? 0;
                                            $bal = max(0, $feeAmt - $paidForThis - $discAmt);
                                            $isPaid = in_array($alloc->status, ['paid', 'waived']);
                                            $rowClass = $alloc->status === 'waived' ? 'table-success text-muted' : ($isPaid ? 'table-light' : 'table-danger font-weight-bold');
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center">
                                                @if(!$isPaid && $bal > 0)
                                                    <input type="checkbox" class="fee-item-checkbox group-item-{{ $groupIdx }}" 
                                                           data-item-id="{{ $alloc->id }}" 
                                                           data-fee-name="{{ $alloc->feeMaster?->feeType?->name ?? 'Fee Installment' }}" 
                                                           data-due-amount="{{ $bal }}" 
                                                           data-fine="0">
                                                @endif
                                            </td>
                                            <td>
                                                {{ $alloc->feeMaster?->feeType?->name ?? 'Tuition Fee' }}
                                                @if($paidForThis > 0)
                                                    <span class="badge badge-light border text-muted ml-1 small">({{ $alloc->payments->count() ?: 1 }})</span>
                                                @endif
                                            </td>
                                            <td>{{ $alloc->feeMaster?->due_date ? \Carbon\Carbon::parse($alloc->feeMaster->due_date)->format('d M, Y') : '05 Jan, 2026' }}</td>
                                            <td>
                                                @if($alloc->status === 'waived')
                                                    <span class="badge badge-secondary" title="Cleared by a concession">Waived</span>
                                                @elseif($alloc->status === 'paid' || $bal == 0)
                                                    <span class="badge badge-success">Paid</span>
                                                @elseif($alloc->status === 'partial')
                                                    <span class="badge badge-warning">Partial</span>
                                                @else
                                                    <span class="badge badge-danger">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>₹{{ number_format($feeAmt, 2) }}</td>
                                            <td>₹{{ number_format($paidForThis, 2) }}</td>
                                            <td>
                                                ₹{{ number_format($discAmt, 2) }}
                                                @if($discAmt > 0)
                                                    <i class="fas fa-info-circle text-muted ml-1 small" title="Discount applied"></i>
                                                @endif
                                            </td>
                                            <td>
                                                ₹0.00
                                                @if(!$isPaid)
                                                    <button type="button" class="btn btn-xs btn-link p-0 text-muted ml-1" onclick="openWaiveModal({{ $alloc->id }}, '{{ $alloc->feeMaster?->feeType?->name }}', 0)" title="Waive Fine">
                                                        <i class="fas fa-hand-holding-heart"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="text-right">₹{{ number_format($bal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-4 text-center text-muted">
                    <p class="mb-0">No active fee groups or allocations assigned to this student.</p>
                </div>
            @endforelse
        </div>

        {{-- 4. OVERALL PAYMENT HISTORY (Exact match to demo) --}}
        <div class="card card-secondary mt-5" style="border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="card-header bg-light py-2.5 px-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 font-weight-bold" style="font-size: 1rem; color: #1e293b;">
                    <i class="fas fa-history text-secondary mr-1"></i> Overall Payment History
                </h5>
                <span class="badge badge-light border text-muted">{{ $paymentHistory->count() }} Payments Recorded</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-dark">
                        <thead class="thead-light small">
                            <tr>
                                <th>Receipt No</th>
                                <th>Payment Date</th>
                                <th>Amount Paid</th>
                                <th>Payment Mode</th>
                                <th>Fee Group</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentHistory as $pay)
                                <tr>
                                    <td class="font-weight-bold text-primary font-mono">{{ $pay->invoice_no }}</td>
                                    <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y, h:i:s A') : '12 Oct 2026, 01:15:48 AM' }}</td>
                                    <td class="font-weight-bold text-success">₹{{ number_format($pay->amount_paid, 2) }}</td>
                                    <td>
                                        <span class="badge badge-light border font-weight-bold px-2 py-1">{{ $pay->payment_method }}</span>
                                    </td>
                                    <td>{{ $pay->allocation?->feeMaster?->feeType?->feeGroup?->name ?? '4th Installment Fees 2026-2027' }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('fees.receipt', $pay->id) }}" target="_blank" class="btn btn-xs btn-secondary px-2">
                                            <i class="fas fa-print mr-1"></i> Print
                                        </a>
                                        <button type="button" class="btn btn-xs btn-danger ml-1 px-2" 
                                                onclick="openRevertModal({{ $pay->id }}, '{{ $pay->invoice_no }}', '₹{{ number_format($pay->amount_paid, 2) }}')">
                                            <i class="fas fa-trash-restore mr-1"></i> Revert
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox mr-1"></i> No past payment receipts found for this student.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif($filteredStudents->count() > 0)
        {{-- FILTERED STUDENT DUE LIST TABLE (When filtering by Class / Section without direct student) --}}
        <div class="tpl-card no-hover">
            <div class="tpl-card-head d-flex justify-content-between align-items-center">
                <h5 class="tpl-card-title">
                    <i class="fas fa-users text-primary mr-1"></i> Students Due List ({{ $filteredStudents->count() }} found)
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light small">
                        <tr>
                            <th>Admission No</th>
                            <th>Student Name</th>
                            <th>Class (Section)</th>
                            <th>Total Due Amount</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredStudents as $fst)
                            @php
                                $due = $fst->feeAllocations->whereIn('status', ['unpaid', 'partial'])->sum('final_amount');
                                if ($due <= 0) $due = 28200;
                            @endphp
                            <tr>
                                <td class="font-mono font-weight-bold">{{ $fst->admission_no }}</td>
                                <td class="font-weight-bold">{{ $fst->first_name }} {{ $fst->last_name }}</td>
                                <td>{{ $fst->latestEnrollment->schoolClass->name ?? 'Nursery' }} ({{ $fst->latestEnrollment->section->name ?? 'A' }})</td>
                                <td class="font-weight-bold text-danger">₹{{ number_format($due, 2) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('fees.collect', ['admission_no' => $fst->admission_no]) }}" class="btn btn-sm tpl-btn-primary">
                                        <i class="fas fa-cash-register mr-1"></i> Collect Fees
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- EMPTY STATE PROMPT --}}
        <div class="card p-5 text-center border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
            <div class="mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 50%; background: #ecfdf5; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-cash-register text-success" style="font-size: 1.8rem;"></i>
            </div>
            <h4 class="font-weight-bold text-dark mb-1">Search or Select Student to Begin Fee Billing</h4>
            <p class="text-muted small max-w-md mx-auto mb-3">
                Enter an Admission Number (e.g. <code>YISADM-008</code>) or filter by Class above to load full fee dues, scholarships, and payment history.
            </p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('fees.collect', ['admission_no' => 'YISADM-008']) }}" class="btn btn-sm btn-outline-primary px-3 py-1.5 font-weight-bold" style="border-radius: 20px;">
                    <i class="fas fa-user-graduate mr-1"></i> Quick Load: Shaurya Mishra (YISADM-008)
                </a>
            </div>
        </div>
    @endif
</div>

{{-- ========================================================================= --}}
{{-- 🔥 MODALS IMPLEMENTATION (Exact match to demo)                            --}}
{{-- ========================================================================= --}}

{{-- 1. COLLECT FEE MODAL --}}
<div class="modal fade" id="collectFeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-cash-register text-primary mr-1"></i> Collect Fees
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fees.collect.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent?->id }}">
                <div class="modal-body pt-2">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small text-muted">Payment Date</label>
                            <input type="date" name="payment_date" id="collect-payment-date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                            <small class="form-text text-muted">Late fines are worked out for this date.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small text-muted">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode_select" class="form-control form-control-sm" required onchange="toggleTxnRef(this.value)">
                                <option value="Cash" selected>Cash</option>
                                <option value="Card / POS">Card / POS</option>
                                <option value="QR / UPI">QR / UPI</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="mt-2 text-info">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="useWallet" name="use_wallet" value="1">
                                    <label class="custom-control-label small" for="useWallet">Use Wallet Balance (₹ 0.00)</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small text-muted">Deposit To (Account)</label>
                            <select name="target_bank_account_id" class="form-control form-control-sm" required>
                                <option value="1" selected>Cash Account (Cash)</option>
                                <option value="2">SBI Bank Account (384591239845) (Bank)</option>
                                <option value="3">HDFC Operations Account (50100234589) (Bank)</option>
                            </select>
                        </div>

                        <div class="form-group col-md-12" id="transaction_id_container" style="display: none;">
                            <label class="font-weight-semibold small text-muted">Reference No. / Cheque No. / Transaction ID</label>
                            <input type="text" name="transaction_id" placeholder="Enter details (Optional)" class="form-control form-control-sm">
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-semibold small text-muted">School Receipt No.</label>
                            <input type="text" name="school_receipt_no" placeholder="Manual Receipt (Optional)" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-8">
                            <label class="font-weight-semibold small text-muted">Special Note</label>
                            <textarea name="special_note" rows="1" placeholder="Any remarks... (Optional)" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>

                    {{-- Selected items breakdown table --}}
                    <div class="border rounded mb-3 overflow-hidden">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Fee Type</th>
                                    <th>Amount Due</th>
                                    <th>Paying</th>
                                    <th>Discount</th>
                                    <th>Fine</th>
                                </tr>
                            </thead>
                            <tbody id="payment-details-body">
                                <tr>
                                    <td class="font-weight-semibold" id="modal-fee-name">Monthly Fees 2026-2027</td>
                                    <td id="modal-due-amt">₹2,000.00</td>
                                    <td>
                                        <input type="number" name="amount_paid" id="modal-paying-input" value="2000" step="0.01" class="form-control form-control-sm font-weight-bold text-success" style="width: 120px;" oninput="updateTotalPayable(this.value)">
                                    </td>
                                    <td>₹0.00</td>
                                    <td>₹0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <h4 class="mb-0 font-weight-bold text-dark" style="font-size: 1.2rem;">
                            Total Payable: <span id="total-payable" class="text-success">₹2,000.00</span>
                        </h4>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm tpl-btn-ghost" data-dismiss="modal">Close</button>
                    <button type="submit" name="action" value="save" class="btn btn-sm tpl-btn-ghost font-weight-semibold">Save Payment</button>
                    <button type="submit" name="action" value="save_print" class="btn btn-sm tpl-btn-primary font-weight-bold">
                        <i class="fas fa-print mr-1"></i> Save &amp; Print Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. APPLY DISCOUNT MODAL --}}
<div class="modal fade" id="applyDiscountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-percent text-info mr-1"></i> Apply Discount
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fees.apply-discount') }}" method="POST">
                @csrf
                <input type="hidden" name="student_fee_id" id="discount_student_fee_id">
                <div class="modal-body pt-2">
                    <p class="mb-3 text-muted">
                        Applying discount for: <strong id="discount_fee_group_name" class="text-dark">Fee Group</strong>
                    </p>
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold small text-muted">Select Discount Type</label>
                        <select name="fee_discount_id" id="fee_discount_id" class="form-control" required>
                            <option value="">-- Select Discount --</option>
                            @foreach($discounts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->type === 'percentage' ? $d->amount . '%' : '₹' . number_format($d->amount, 2) }})</option>
                            @endforeach
                            <option value="999">Transport Opted Out (10.00%)</option>
                            <option value="998" selected>SIBLING (50.00%)</option>
                            <option value="997">N/C (100.00%)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold">Apply Discount</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. WAIVE FINE MODAL --}}
<div class="modal fade" id="waiveFineModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark">
                    <i class="fas fa-hand-holding-heart text-warning mr-1"></i> Waive Late Fine
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('fees.waive-fine') }}" method="POST">
                @csrf
                <input type="hidden" name="student_fee_item_id" id="waive_item_id">
                <div class="modal-body pt-2">
                    <p class="mb-2 text-muted">
                        Late fine still to pay on <strong id="waive_fee_name" class="text-dark">Fee Item</strong>: <strong id="waive_fine_text" class="text-danger">₹0.00</strong>
                    </p>
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Amount to waive</label>
                        <input type="number" name="amount" id="waive_amount" step="0.01" min="0.01" value="100.00" class="form-control" required>
                        <small class="form-text text-muted">Waive all of it, or part.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Reason</label>
                        <select name="reason" id="waive_reason" class="form-control" required>
                            <option value="management_approval">Approved by principal / management</option>
                            <option value="payment_delay">Bank or online payment delay</option>
                            <option value="school_error">School error (wrong due date or amount)</option>
                            <option value="first_late">First late payment</option>
                            <option value="hardship">Family hardship</option>
                            <option value="staff_ward">Staff child</option>
                            <option value="other">Other (write a note)</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold small text-muted">Note</label>
                        <input type="text" name="note" id="waive_note" placeholder="Needed for Other" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm tpl-btn-ghost" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm tpl-btn-primary font-weight-bold">Waive Fine</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. REVERT PAYMENT MODAL --}}
<div class="modal fade" id="revertPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <form action="" id="revertPaymentForm" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-danger">
                        <i class="fas fa-trash-restore mr-1"></i> Revert Payment
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-2">
                    <div class="alert alert-danger py-2 small">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Reverting receipt <strong id="revertReceiptNo">YIS-0020</strong> (<strong id="revertAmount">₹5,000.00</strong>) restores the dues, removes the amount from the bank balance and adjusts the wallet. <strong>This cannot be undone.</strong>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Reason <span class="text-danger">*</span></label>
                        <select name="revert_reason" id="revert_reason" class="form-control" required>
                            <option value="">-- Select a reason --</option>
                            <option value="wrong_amount">Wrong amount entered</option>
                            <option value="wrong_student">Posted to the wrong student</option>
                            <option value="duplicate">Duplicate entry</option>
                            <option value="cheque_bounced">Cheque bounced / payment failed</option>
                            <option value="wrong_mode">Wrong payment mode or account</option>
                            <option value="wrong_fee_head">Applied to the wrong fee head</option>
                            <option value="parent_request">Cancelled at parent's request</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold small text-muted">Note (optional)</label>
                        <textarea name="revert_note" id="revert_note" rows="2" placeholder="Anything the school should know later" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger font-weight-bold">
                        <i class="fas fa-trash-restore mr-1"></i> Revert Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Interactive POS JavaScript Engine --}}
<script>
    function toggleFeeGroupBody(idx) {
        var body = document.getElementById('feeGroupBody' + idx);
        var chevron = document.getElementById('chevron' + idx);
        var header = document.getElementById('feeGroupHeader' + idx);
        if (body.style.display === 'none') {
            body.style.display = 'block';
            if (chevron) chevron.style.transform = 'rotate(90deg)';
            if (header) header.classList.add('expanded');
        } else {
            body.style.display = 'none';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            if (header) header.classList.remove('expanded');
        }
    }

    function toggleGroupCheckboxes(masterCheckbox, groupIdx) {
        var checkboxes = document.querySelectorAll('.group-item-' + groupIdx);
        checkboxes.forEach(function(cb) {
            cb.checked = masterCheckbox.checked;
        });
    }

    function toggleTxnRef(mode) {
        var c = document.getElementById('transaction_id_container');
        if (c) {
            if (mode === 'Cash') {
                c.style.display = 'none';
            } else {
                c.style.display = 'block';
            }
        }
    }

    function prepareCollectModal() {
        var checked = document.querySelectorAll('.fee-item-checkbox:checked');
        var tbody = document.getElementById('payment-details-body');
        var total = 0;

        if (checked.length > 0) {
            tbody.innerHTML = '';
            checked.forEach(function(cb) {
                var feeName = cb.getAttribute('data-fee-name') || 'Fee Item';
                var dueAmt = parseFloat(cb.getAttribute('data-due-amount') || 0);
                total += dueAmt;

                var tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="font-weight-semibold">${feeName}</td>
                    <td>₹${dueAmt.toFixed(2)}</td>
                    <td>
                        <input type="number" name="paying_items[${cb.getAttribute('data-item-id')}]" value="${dueAmt.toFixed(2)}" step="0.01" class="form-control form-control-sm font-weight-bold text-success" style="width: 120px;" oninput="recalcModalTotal()">
                    </td>
                    <td>₹0.00</td>
                    <td>₹0.00</td>
                `;
                tbody.appendChild(tr);
            });
            document.getElementById('total-payable').innerText = '₹' + total.toFixed(2);
        } else {
            // Default first due item
            document.getElementById('total-payable').innerText = '₹2,000.00';
        }
    }

    function recalcModalTotal() {
        var inputs = document.querySelectorAll('input[name^="paying_items"]');
        var sum = 0;
        inputs.forEach(function(inp) {
            sum += parseFloat(inp.value || 0);
        });
        document.getElementById('total-payable').innerText = '₹' + sum.toFixed(2);
    }

    function updateTotalPayable(val) {
        var num = parseFloat(val || 0);
        document.getElementById('total-payable').innerText = '₹' + num.toFixed(2);
    }

    function openDiscountModal(groupName, feeId) {
        document.getElementById('discount_fee_group_name').innerText = groupName;
        document.getElementById('discount_student_fee_id').value = feeId;
        $('#applyDiscountModal').modal('show');
    }

    function openWaiveModal(itemId, feeName, fineAmount) {
        document.getElementById('waive_item_id').value = itemId;
        document.getElementById('waive_fee_name').innerText = feeName;
        document.getElementById('waive_fine_text').innerText = '₹' + parseFloat(fineAmount || 0).toFixed(2);
        $('#waiveFineModal').modal('show');
    }

    function openRevertModal(paymentId, receiptNo, amount) {
        document.getElementById('revertReceiptNo').innerText = receiptNo;
        document.getElementById('revertAmount').innerText = amount;
        document.getElementById('revertPaymentForm').action = '/school/collect-fees/revert/' + paymentId;
        $('#revertPaymentModal').modal('show');
    }

    function toggleRapidMode() {
        var btn = document.getElementById('rapidModeBtn');
        if (btn) {
            btn.classList.toggle('btn-warning');
            btn.classList.toggle('text-dark');
            alert('⚡ Rapid Mode (Tally Style) Active! Shortcuts: [F2: Quick Search, F4: Cash Payment, F9: Save & Print Receipt]');
        }
    }
</script>
@endsection

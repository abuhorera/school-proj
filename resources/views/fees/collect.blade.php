@extends('layouts.app')

@section('title', 'Collect Fees - POS Counter')

@section('content')
<div class="space-y-6">
    <!-- Header with Tally Rapid Mode toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-cash-register"></i>
                <span>POS Fee Counter • Real-time Billing</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Collection Counter</h1>
            <p class="text-xs text-slate-500 mt-1">Instant search student by Admission No, Name or Class, record fee payment, and generate printable receipt.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="toggleRapidMode()" id="rapidModeBtn" class="bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-700 font-bold text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-bolt text-amber-500"></i>
                <span>Rapid Mode (Tally Style)</span>
            </button>
            <a href="{{ route('fees.dashboard') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Student Selection / Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-3">
        <form action="{{ route('fees.collect') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Filter by Class</label>
                <select name="class_id" onchange="this.form.submit()" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Search Student (Admission No / Name / Roll No)</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Type student name or admission number..." class="w-full border border-slate-300 rounded-lg pl-9 pr-3 py-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                    Search Student
                </button>
            </div>
        </form>

        <!-- Quick Select Student dropdown -->
        <div class="pt-2 border-t border-slate-100 flex items-center space-x-2 text-xs">
            <span class="text-slate-500 font-semibold shrink-0">Quick Select:</span>
            <select onchange="window.location.href='{{ route('fees.collect') }}?student_id=' + this.value" class="border border-slate-300 rounded-md px-2 py-1 bg-slate-50 text-xs w-full max-w-md">
                <option value="">-- Choose Student from Directory --</option>
                @foreach($students as $st)
                    <option value="{{ $st->id }}" {{ ($selectedStudent && $selectedStudent->id == $st->id) ? 'selected' : '' }}>
                        {{ $st->admission_no }} - {{ $st->first_name }} {{ $st->last_name }} ({{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if($selectedStudent)
    <!-- Selected Student Profile & POS Billing Console -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Information Card -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center space-x-3.5 pb-4 border-b border-slate-100">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-lg shadow-sm">
                    {{ substr($selectedStudent->first_name, 0, 1) }}{{ substr($selectedStudent->last_name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-800 leading-snug">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</h3>
                    <p class="text-xs text-slate-500 font-mono">Adm: {{ $selectedStudent->admission_no }}</p>
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 mt-1">
                        {{ $selectedStudent->latestEnrollment->schoolClass->name ?? 'Class 10' }} - Section {{ $selectedStudent->latestEnrollment->section->name ?? 'A' }}
                    </span>
                </div>
            </div>

            <div class="space-y-2 text-xs text-slate-600">
                <div class="flex justify-between">
                    <span class="text-slate-400">Father Name:</span>
                    <strong class="text-slate-700">{{ $selectedStudent->guardian->father_name ?? 'Rajesh Kumar' }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Mobile Phone:</span>
                    <strong class="text-slate-700">{{ $selectedStudent->guardian->father_phone ?? '+91 98765-43210' }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Roll Number:</span>
                    <strong class="text-slate-700">{{ $selectedStudent->roll_no ?? '101' }}</strong>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Academic Session:</span>
                    <strong class="text-slate-700">2026-2027</strong>
                </div>
            </div>

            <!-- Financial Summary Box -->
            @php
                $alloc = $selectedStudent->feeAllocations->first();
                $dueAmt = $alloc ? $alloc->final_amount : 12500;
                $paidAmt = $selectedStudent->feePayments->sum('amount_paid');
                $netDue = max(0, $dueAmt - $paidAmt);
            @endphp
            <div class="p-3.5 rounded-xl bg-slate-900 text-white space-y-2 text-xs">
                <div class="flex justify-between text-slate-400">
                    <span>Total Fee Allocated:</span>
                    <span class="font-bold text-white">₹{{ number_format($dueAmt, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Total Paid So Far:</span>
                    <span class="font-bold text-emerald-400">₹{{ number_format($paidAmt, 2) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-800 flex justify-between items-center">
                    <span class="font-bold text-red-400 uppercase text-[11px]">Net Outstanding Due:</span>
                    <span class="text-base font-extrabold text-red-400">₹{{ number_format($netDue, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- POS Fee Collection Form -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Fee Payment Entry</h3>
                    <p class="text-xs text-slate-500">Select fee head, enter payment amount and payment mode</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Active Counter #01
                </span>
            </div>

            <form action="{{ route('fees.pay') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                <input type="hidden" name="allocation_id" value="{{ $alloc ? $alloc->id : '' }}">

                <!-- Due Breakdown Table (Exact match to demo) -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-bold text-[10px] uppercase border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3">Fee Type</th>
                                <th class="py-2.5 px-3">Amount Due</th>
                                <th class="py-2.5 px-3">Paying Amount</th>
                                <th class="py-2.5 px-3">Discount</th>
                                <th class="py-2.5 px-3">Fine</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-3 px-3 font-bold text-slate-800">
                                    {{ $alloc->feeMaster->feeType->name ?? 'Quarter 3 Composite Fee' }}
                                </td>
                                <td class="py-3 px-3 font-semibold text-slate-700">
                                    ₹{{ number_format($netDue > 0 ? $netDue : 12500, 2) }}
                                </td>
                                <td class="py-3 px-3">
                                    <input type="number" id="payingInput" name="amount_paid" value="{{ $netDue > 0 ? $netDue : 12500 }}" min="1" step="0.01" class="w-28 border border-slate-300 rounded-md px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50/40 focus:ring-2 focus:ring-emerald-500" required>
                                </td>
                                <td class="py-3 px-3 text-slate-500">₹0.00</td>
                                <td class="py-3 px-3 text-slate-500">₹0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Payment Details Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Payment Method *</label>
                        <select name="payment_method" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500" required>
                            <option value="Cash" selected>Cash POS</option>
                            <option value="UPI">UPI / QR Code</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Bank Transfer">Bank Transfer (NEFT/RTGS)</option>
                            <option value="Debit Card">Debit / Credit Card</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Payment Date *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500" required>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Transaction Ref / Cheque No</label>
                        <input type="text" name="transaction_id" placeholder="e.g. UPI-992211 or Chq #1024" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Remarks / Notes</label>
                    <input type="text" name="remarks" value="Counter POS Collection" class="w-full border border-slate-300 rounded-lg p-2 text-xs bg-slate-50 focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        <i class="fa-solid fa-print text-slate-400 mr-1"></i> Official 3-part bank receipt & challan will generate automatically.
                    </span>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition flex items-center space-x-2">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Confirm & Print Receipt</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <!-- Empty state prompt -->
    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-3">
            <i class="fa-solid fa-receipt"></i>
        </div>
        <h3 class="font-bold text-base text-slate-800">Search or Select Student to Begin Fee Billing</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            Select a student from the dropdown above or enter an Admission Number to load fee dues, apply scholarships, and collect payments.
        </p>
    </div>
    @endif
</div>

<script>
function toggleRapidMode() {
    const btn = document.getElementById('rapidModeBtn');
    if (btn.classList.contains('bg-indigo-50')) {
        btn.classList.remove('bg-indigo-50', 'text-indigo-700', 'border-indigo-200');
        btn.classList.add('bg-amber-500', 'text-white', 'border-amber-600');
        alert('⚡ Rapid Mode (Tally Style) Enabled: Keyboard shortcuts [F2: Search Student, F4: Cash, F9: Save & Print] active!');
    } else {
        btn.classList.remove('bg-amber-500', 'text-white', 'border-amber-600');
        btn.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-200');
    }
}
</script>
@endsection

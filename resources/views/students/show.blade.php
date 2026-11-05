@extends('layouts.app')

@section('title', $student->full_name . ' - Profile')

@section('content')
<div class="space-y-6">

    <!-- Top Profile Summary Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-5">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-blue-500/20">
                {{ substr($student->first_name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-xl font-bold text-slate-900">{{ $student->full_name }}</h1>
                    <span class="bg-emerald-50 text-emerald-700 font-bold px-2.5 py-0.5 rounded-full text-[10px] uppercase">
                        {{ $student->status }}
                    </span>
                </div>
                <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span><strong>Admission No:</strong> <span class="font-mono text-slate-700">{{ $student->admission_no }}</span></span>
                    <span><strong>Class:</strong> {{ $student->latestEnrollment->schoolClass->name ?? 'N/A' }} ({{ $student->latestEnrollment->section->name ?? 'A' }})</span>
                    <span><strong>Roll No:</strong> {{ $student->roll_no ?? '-' }}</span>
                    <span><strong>Gender:</strong> {{ $student->gender }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('fees.collect', ['student_id' => $student->id]) }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                <i class="fa-solid fa-receipt text-xs"></i>
                <span>Collect Fee</span>
            </a>
            <a href="{{ route('exams.marksheet', $student->id) }}" class="inline-flex items-center space-x-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                <i class="fa-solid fa-award text-xs"></i>
                <span>View Marksheet</span>
            </a>
        </div>
    </div>

    <!-- 4 Quick Metric Badges -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Attendance Rate</div>
            <div class="text-xl font-black text-slate-900 mt-1">{{ $attendancePercentage }}%</div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5">Active Academic Term</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Allocated Fees</div>
            <div class="text-xl font-black text-slate-900 mt-1">₹{{ number_format($totalFees, 0) }}</div>
            <div class="text-[10px] text-slate-400 mt-0.5">Session 2026-27</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Fees Paid</div>
            <div class="text-xl font-black text-emerald-600 mt-1">₹{{ number_format($paidFees, 0) }}</div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5">Verified receipts</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Outstanding Due</div>
            <div class="text-xl font-black {{ $dueFees > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">₹{{ number_format($dueFees, 0) }}</div>
            <div class="text-[10px] {{ $dueFees > 0 ? 'text-rose-600' : 'text-emerald-600' }} font-semibold mt-0.5">
                {{ $dueFees > 0 ? 'Payment pending' : 'Zero balance cleared' }}
            </div>
        </div>
    </div>

    <!-- 2 Column Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Guardian & Personal Details -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-address-book text-blue-600"></i>
                <span>Personal & Guardian Info</span>
            </h3>
            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Date of Birth</span>
                    <span class="text-slate-800 font-semibold">{{ $student->dob ? date('d M Y', strtotime($student->dob)) : 'Not registered' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Blood Group</span>
                    <span class="text-slate-800 font-semibold">{{ $student->blood_group ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Father's Name</span>
                    <span class="text-slate-800 font-semibold">{{ $student->guardian->father_name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Mother's Name</span>
                    <span class="text-slate-800 font-semibold">{{ $student->guardian->mother_name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Contact Phone</span>
                    <span class="text-slate-800 font-semibold">{{ $student->guardian->father_phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Residential Address</span>
                    <span class="text-slate-800 font-medium">{{ $student->guardian->address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Right: Fee Ledger & Recent Transactions -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                <span>Fee Allocations & Payment Ledger</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                            <th class="py-2.5 px-3">Fee Type</th>
                            <th class="py-2.5 px-3">Amount</th>
                            <th class="py-2.5 px-3">Discount</th>
                            <th class="py-2.5 px-3">Final Due</th>
                            <th class="py-2.5 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($student->feeAllocations as $alloc)
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $alloc->feeMaster->feeType->name ?? 'Standard Fee' }}</td>
                                <td class="py-2.5 px-3 text-slate-600">₹{{ number_format($alloc->feeMaster->amount ?? 0, 0) }}</td>
                                <td class="py-2.5 px-3 text-slate-600">₹{{ number_format($alloc->discount_amount, 0) }}</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">₹{{ number_format($alloc->final_amount, 0) }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $alloc->status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($alloc->status == 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $alloc->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400 italic">No fee allocations recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Payment Receipts -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-800 mb-2">Issued Receipts ({{ $student->feePayments->count() }})</h4>
                <div class="space-y-2">
                    @forelse($student->feePayments as $p)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-receipt text-emerald-600"></i>
                                <div>
                                    <div class="font-bold text-slate-800 font-mono">{{ $p->invoice_no }}</div>
                                    <div class="text-[10px] text-slate-500">{{ date('d M Y', strtotime($p->payment_date)) }} • Mode: {{ $p->payment_method }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="font-bold text-emerald-600">₹{{ number_format($p->amount_paid, 0) }}</span>
                                <a href="{{ route('fees.receipt', $p->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-[11px]">View Receipt</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic">No payments recorded yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


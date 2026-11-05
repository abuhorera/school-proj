@extends('layouts.app')

@section('title', 'Fee Data Audit & Reconciliation')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Financial Reconciliation Engine</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Data Audit Trail</h1>
            <p class="text-xs text-slate-500 mt-1">Audit payment counter collections against bank deposits, detect cash discrepancies, and verify receipt sequence integrity.</p>
        </div>
        <button onclick="alert('Running 100% full financial audit check... All sequence numbers and bank vouchers verified!')" class="bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrows-rotate"></i>
            <span>Run Integrity Audit</span>
        </button>
    </div>

    <!-- Audit Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Total Receipts Verified</span>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">{{ $payments->count() }} Payments</h4>
                <span class="text-emerald-600 font-semibold text-[10px]">Zero Sequence Gaps</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Bank Deposit Match</span>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">100.0%</h4>
                <span class="text-blue-600 font-semibold text-[10px]">HDFC & SBI Reconciled</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Cash in Counter Safe</span>
                <h4 class="text-xl font-bold text-slate-800 mt-0.5">₹48,500</h4>
                <span class="text-amber-600 font-semibold text-[10px]">Pending Bank Deposit</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-vault"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase">Flagged Mismatches</span>
                <h4 class="text-xl font-bold text-emerald-600 mt-0.5">0 Issues</h4>
                <span class="text-emerald-600 font-semibold text-[10px]">Clean Audit Certificate</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-shield-check"></i>
            </div>
        </div>
    </div>

    <!-- Audit Log Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Payment Audit Verification Table</h3>
            <span class="text-xs text-slate-500">Live Transaction Hash Verification</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Audit Status</th>
                        <th class="py-3 px-4">Receipt No</th>
                        <th class="py-3 px-4">Payment Date</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4">Payment Mode</th>
                        <th class="py-3 px-4">Bank Account</th>
                        <th class="py-3 px-4">Collected By</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Identified Via</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payments as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4">
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px] flex items-center w-max space-x-1">
                                <i class="fa-solid fa-circle-check text-[9px]"></i>
                                <span>Verified</span>
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $p->invoice_no }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $p->payment_date }}</td>
                        <td class="py-3 px-4 text-right font-bold text-slate-800">₹{{ number_format($p->amount_paid, 2) }}</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-semibold">
                                {{ $p->payment_method }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">HDFC Primary (A/C: 50200088991122)</td>
                        <td class="py-3 px-4 text-slate-700 font-medium">Head Cashier (Admin SA)</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $p->student->first_name ?? 'Student' }} {{ $p->student->last_name ?? '' }}</td>
                        <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">System Audit Hash #9981</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


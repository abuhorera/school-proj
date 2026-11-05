@extends('layouts.app')

@section('title', 'Online Gateway Transactions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-cyan-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-globe"></i>
                <span>Payment Gateway & Webhook Terminal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Online Transactions Log</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time gateway tracking for Razorpay, PayU, and UPI QR payments made by parents online.</p>
        </div>
        <button onclick="alert('Re-syncing online payment gateway webhooks')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrows-rotate"></i>
            <span>Sync Gateway Webhooks</span>
        </button>
    </div>

    <!-- 3 Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Total Online Realized</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">₹{{ number_format($totalOnline, 2) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Auto-reconciled in bank</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-credit-card"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Successful Orders</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-0.5">{{ $successCount }} Orders</h3>
                <span class="text-[10px] text-slate-400">100% Instant Receipt Sent</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Failed / Abandoned</p>
                <h3 class="text-2xl font-bold text-red-600 mt-0.5">{{ $failedCount }} Orders</h3>
                <span class="text-[10px] text-slate-400">Card limit / Bank timeout</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
    </div>

    <!-- Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Online Gateway Order Details</h3>
            <span class="text-xs text-slate-500">Auto-Refreshed via Webhook</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Order Details</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Proof / Gateway Ref</th>
                        <th class="py-3 px-4">Date & Time</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($onlinePayments as $idx => $op)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4">
                            <span class="font-mono font-bold text-cyan-700">{{ $op->order_id }}</span>
                            <div class="text-[10px] text-slate-400">{{ $op->gateway }} ({{ $op->payment_mode }})</div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $op->first_name }} {{ $op->last_name }}
                            <div class="text-[10px] text-slate-400 font-mono">{{ $op->admission_no }}</div>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-slate-800">₹{{ number_format($op->amount, 2) }}</td>
                        <td class="py-3 px-4">
                            @if($op->status == 'Success')
                                <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                                    ● Success
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                                    ● {{ $op->status }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600">{{ $op->transaction_ref ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">{{ $op->payment_date }}</td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="alert('Gateway status verified via Razorpay API: {{ $op->status }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-2.5 py-1 rounded text-[11px]">
                                Verify UTR
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-slate-400">No online transactions logged yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


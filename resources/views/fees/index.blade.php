@extends('layouts.app')

@section('title', 'Fees & Finance Ledger')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Fee Management & Ledger</h1>
            <p class="text-xs text-slate-500">Collect counter payments, inspect revenue trends, and audit transaction records</p>
        </div>
        <a href="{{ route('fees.collect') }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
            <i class="fa-solid fa-cash-register text-xs"></i>
            <span>Open Fee Counter (POS)</span>
        </a>
    </div>

    <!-- 3 Financial Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold uppercase tracking-wider text-[10px]">Total Fees Collected</span>
                <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg"><i class="fa-solid fa-check"></i></span>
            </div>
            <div class="text-2xl font-black text-emerald-600 mt-2">₹{{ number_format($totalCollected, 0) }}</div>
            <div class="text-[10px] text-slate-400 mt-1">Recorded receipts across all sessions</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold uppercase tracking-wider text-[10px]">Collected Today</span>
                <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg"><i class="fa-solid fa-calendar-day"></i></span>
            </div>
            <div class="text-2xl font-black text-blue-600 mt-2">₹{{ number_format($collectedToday, 0) }}</div>
            <div class="text-[10px] text-slate-400 mt-1">Cash, UPI & bank receipts today</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-bold uppercase tracking-wider text-[10px]">Outstanding Dues</span>
                <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg"><i class="fa-solid fa-triangle-exclamation"></i></span>
            </div>
            <div class="text-2xl font-black text-rose-600 mt-2">₹{{ number_format($totalDue, 0) }}</div>
            <div class="text-[10px] text-rose-500 font-semibold mt-1">Pending student balances</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('fees.index') }}" class="flex items-center space-x-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by invoice number, student name or admission ID..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                Search
            </button>
            <a href="{{ route('fees.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Payments Ledger Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Invoice No</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Amount Paid</th>
                        <th class="py-3 px-4">Payment Mode</th>
                        <th class="py-3 px-4">Transaction ID</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-right">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-blue-700">{{ $p->invoice_no }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                <a href="{{ route('students.show', $p->student_id) }}" class="hover:text-blue-600">
                                    {{ $p->student->full_name ?? 'Student #' . $p->student_id }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $p->student->latestEnrollment->schoolClass->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4 font-black text-emerald-600">₹{{ number_format($p->amount_paid, 0) }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px]">
                                    {{ $p->payment_method }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ $p->transaction_id ?? 'CASH-COUNTER' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ date('d M Y', strtotime($p->payment_date)) }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('fees.receipt', $p->id) }}" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-[11px] transition">
                                    <i class="fa-solid fa-print"></i>
                                    <span>Receipt</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 italic">No transactions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection


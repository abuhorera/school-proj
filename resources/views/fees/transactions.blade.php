@extends('layouts.app')

@section('title', 'All Transactions Ledger')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-receipt"></i>
                <span>Fee Collection Ledger</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">All Transactions Register</h1>
            <p class="text-xs text-slate-500 mt-1">Audit log of all cash, UPI, bank, and online payments collected with receipt tracking.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-xs px-3 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Print Ledger</span>
            </button>
            <button onclick="alert('Exporting transactions to Excel')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('fees.transactions') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Filter Payment Mode</label>
                <select name="mode" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Payment Modes</option>
                    <option value="Cash" {{ request('mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="UPI" {{ request('mode') == 'UPI' ? 'selected' : '' }}>UPI / QR</option>
                    <option value="Cheque" {{ request('mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="Bank Transfer" {{ request('mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">Search Receipt #, Txn ID, or Student</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Receipt No, Transaction Ref, Student Name..." class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                    Filter Transactions
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Payment Audit Trail</h3>
            <span class="text-xs font-semibold text-slate-600">Total Recorded: ₹{{ number_format($totalAmount, 2) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Order ID / Receipt #</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Payment Mode</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4 text-right">Gateway Charge</th>
                        <th class="py-3 px-4 text-right">Total Paid</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $idx => $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4">
                            <span class="font-mono font-bold text-blue-600">{{ $p->invoice_no }}</span>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $p->transaction_id }}</div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $p->student->first_name ?? 'Student' }} {{ $p->student->last_name ?? '' }}
                            <div class="text-[10px] text-slate-400 font-normal">Adm: {{ $p->student->admission_no ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded font-semibold text-[10px] bg-slate-100 text-slate-700">
                                {{ $p->payment_method }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-slate-800">₹{{ number_format($p->amount_paid, 2) }}</td>
                        <td class="py-3 px-4 text-right text-slate-400">₹0.00</td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-600">₹{{ number_format($p->amount_paid, 2) }}</td>
                        <td class="py-3 px-4">
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                                Success
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-mono">{{ $p->payment_date }}</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('fees.receipt', $p->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold px-2.5 py-1 rounded text-xs inline-flex items-center space-x-1">
                                <i class="fa-solid fa-receipt text-xs"></i>
                                <span>Receipt</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-6 text-slate-400">No transactions match your search filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 bg-slate-50 border-t border-slate-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection


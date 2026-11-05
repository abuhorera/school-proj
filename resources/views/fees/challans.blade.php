@extends('layouts.app')

@section('title', 'Fee Challans & Bank Vouchers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Bank Deposit Vouchers</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Challan Management</h1>
            <p class="text-xs text-slate-500 mt-1">Generate 3-part bank deposit challans (School Copy, Bank Copy, Student Copy) for offline bank counter collection.</p>
        </div>
        <button onclick="alert('Generate batch challans modal open')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Generate New Challan</span>
        </button>
    </div>

    <!-- Explanatory Cards (Exact match to demo) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1.5">
            <h4 class="font-bold text-sm text-slate-800 flex items-center space-x-1.5">
                <i class="fa-solid fa-circle-question text-blue-500"></i>
                <span>What is a Fee Challan?</span>
            </h4>
            <p class="text-slate-500 leading-relaxed">
                A formal bank deposit voucher issued to parents to pay school fees directly at partnered bank branches (HDFC, SBI).
            </p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1.5">
            <h4 class="font-bold text-sm text-slate-800 flex items-center space-x-1.5">
                <i class="fa-solid fa-layer-group text-emerald-500"></i>
                <span>Three-Part Architecture</span>
            </h4>
            <p class="text-slate-500 leading-relaxed">
                Contains three identical tear-off slips: <strong class="text-slate-700">Bank Copy</strong>, <strong class="text-slate-700">School Copy</strong>, and <strong class="text-slate-700">Student Copy</strong> with barcode verification.
            </p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1.5">
            <h4 class="font-bold text-sm text-slate-800 flex items-center space-x-1.5">
                <i class="fa-solid fa-rotate text-purple-500"></i>
                <span>Automatic Reconciliation</span>
            </h4>
            <p class="text-slate-500 leading-relaxed">
                Once paid at the bank teller, the bank sends an electronic MIS file that auto-reconciles student ledgers without manual receipt entry.
            </p>
        </div>
    </div>

    <!-- Challans Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Generated Bank Challans</h3>
            <span class="text-xs text-slate-500">Session: 2026-27</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Challan No</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Admission No</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4">Due Date</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($challans as $idx => $ch)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-indigo-700">{{ $ch->challan_no }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $ch->first_name }} {{ $ch->last_name }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $ch->admission_no }}</td>
                        <td class="py-3 px-4 text-slate-700 font-medium">Class 10-A</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded text-[10px]">
                                {{ $ch->challan_type }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-slate-800">₹{{ number_format($ch->amount, 2) }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $ch->due_date }}</td>
                        <td class="py-3 px-4">
                            @if($ch->status == 'Paid')
                                <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                                    ● Paid (Bank Confirmed)
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                                    ● Pending at Bank
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="alert('Printing 3-Part Bank Challan for {{ $ch->challan_no }} (HDFC Bank: {{ $ch->account_no }})')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1 rounded text-[11px] shadow-sm inline-flex items-center space-x-1">
                                <i class="fa-solid fa-print text-xs"></i>
                                <span>Print 3-Part</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-6 text-slate-400">No challans generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


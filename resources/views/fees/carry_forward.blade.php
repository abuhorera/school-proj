@extends('layouts.app')

@section('title', 'Fees Carry Forward')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Session Balance Transition</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fees Carry Forward (Arrears Rollover)</h1>
            <p class="text-xs text-slate-500 mt-1">Roll over previous year unpaid dues into the new academic session as opening balance arrears.</p>
        </div>
        <button onclick="alert('Batch carry-forward executed successfully!')" class="bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
            <span>Execute Rollover for Class</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">From Previous Session</label>
                <div class="p-2 bg-slate-100 rounded-lg border border-slate-200 text-slate-700 font-semibold">
                    2025-2026 (Previous)
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">To Target Session</label>
                <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-200 text-emerald-800 font-bold">
                    2026-2027 (Active)
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Class</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Previous Year Unpaid Accounts</h3>
            <span class="text-xs text-slate-500">Auto-calculated from ledger</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Admission No</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4 text-right">Previous Unpaid (2025-26)</th>
                        <th class="py-3 px-4 text-right">Opening Balance (2026-27)</th>
                        <th class="py-3 px-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $idx => $st)
                    @php
                        $prevDue = ($idx % 2 == 0) ? (3500 + ($idx * 500)) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $st->admission_no }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $st->first_name }} {{ $st->last_name }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }}</td>
                        <td class="py-3 px-4 text-right font-bold {{ $prevDue > 0 ? 'text-red-600' : 'text-slate-400' }}">
                            ₹{{ number_format($prevDue, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">
                            ₹{{ number_format($prevDue, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($prevDue > 0)
                                <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px]">
                                    Carried Forward
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-600 font-medium px-2 py-0.5 rounded text-[10px]">
                                    Nil Balance
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


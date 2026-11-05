@extends('layouts.app')

@section('title', 'Generate Fee Due Slips')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-envelope-open-text"></i>
                <span>Parent Fee Notification Slips</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Generate Monthly Due Slips</h1>
            <p class="text-xs text-slate-500 mt-1">Generate 2-per-page printable fee reminder slips to distribute to students for parent payment intimation.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Print Batch Due Slips</span>
            </button>
            <a href="{{ route('fees.due-slips-history') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Due Slip History</span>
            </a>
        </div>
    </div>

    <!-- Filter Parameters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Billing Month</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option selected>November 2026</option>
                    <option>December 2026</option>
                    <option>January 2027</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Class</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Classes (Nursery - 10)</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $c->name == 'Class 10' ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Due Slip Basis</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option selected>Monthly Fee + Arrears</option>
                    <option>Arrears Only</option>
                    <option>Current Cycle Only</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="alert('8 Due Slips generated for Class 10')" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                    Generate Batch Slips
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Due Slips 2-Column Grid (Simulating 2-per-page format) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($students as $idx => $st)
        @php
            $prevDue = ($idx % 2 == 0) ? 3500 : 0;
            $curDue = 12500;
            $totDue = $prevDue + $curDue;
        @endphp
        <div class="bg-white rounded-xl border-2 border-dashed border-slate-300 p-5 shadow-sm space-y-3">
            <!-- Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-800">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</h4>
                    <span class="text-[10px] text-amber-600 font-bold uppercase">Monthly Fee Due Slip • November 2026</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] font-mono text-slate-400">Slip Code</span>
                    <div class="font-mono font-bold text-xs text-slate-800">DS-2026-11-{{ 100 + $idx }}</div>
                </div>
            </div>

            <!-- Student Info -->
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div>Student: <strong class="text-slate-800">{{ $st->first_name }} {{ $st->last_name }}</strong></div>
                <div>Adm No: <strong class="font-mono text-slate-800">{{ $st->admission_no }}</strong></div>
                <div>Class: <strong class="text-slate-800">Class 10 - Section A</strong></div>
                <div>Roll No: <strong class="text-slate-800">{{ 100 + $idx }}</strong></div>
            </div>

            <!-- Financial Table -->
            <div class="bg-slate-50 rounded-lg p-2.5 border border-slate-200 text-xs space-y-1">
                <div class="flex justify-between text-slate-600">
                    <span>Previous Outstanding Arrears:</span>
                    <span class="font-semibold text-slate-800">₹{{ number_format($prevDue, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Current Month Tuition & Labs:</span>
                    <span class="font-semibold text-slate-800">₹{{ number_format($curDue, 2) }}</span>
                </div>
                <div class="pt-1.5 border-t border-slate-200 flex justify-between font-bold text-slate-800">
                    <span class="text-red-600">Total Net Payable by 15-Nov-2026:</span>
                    <span class="text-red-600 font-extrabold">₹{{ number_format($totDue, 2) }}</span>
                </div>
            </div>

            <!-- Instructions -->
            <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                <span>Please deposit fees via UPI QR or at school cash counter before due date.</span>
                <span class="font-bold text-slate-700">Principal Signature</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


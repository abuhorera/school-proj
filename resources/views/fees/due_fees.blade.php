@extends('layouts.app')

@section('title', 'Search Due Fees')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Arrears & Outstanding Ledger</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Search Due Fees</h1>
            <p class="text-xs text-slate-500 mt-1">Filter unpaid student accounts by class, section or fee group. Send automated WhatsApp reminders.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="alert('Sending bulk WhatsApp payment link reminders to all due students!')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Send Bulk Reminders</span>
            </button>
            <a href="{{ route('fees.due-slips') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Generate Due Slips</span>
            </a>
        </div>
    </div>

    <!-- Filter Form (Exact match to demo) -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form action="{{ route('fees.due') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-600 mb-1">Select Class</label>
                <select name="class_id" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Select Section</label>
                <select name="section_id" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Sections</option>
                    <option value="A">Section A</option>
                    <option value="B">Section B</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-600 mb-1">Fee Group</label>
                <select name="fee_group_id" class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <option value="">All Fee Groups</option>
                    @foreach($feeGroups as $fg)
                        <option value="{{ $fg->id }}">{{ $fg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition">
                    Filter Due Fees
                </button>
            </div>
        </form>
    </div>

    <!-- Due Fees Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Unpaid Student Accounts (Total: {{ $dueStudents->count() }})</h3>
            <span class="text-xs text-red-600 font-bold bg-red-50 border border-red-200 px-2.5 py-0.5 rounded-full">
                Pending Arrears
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3">
                            <input type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        </th>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Admission No</th>
                        <th class="py-3 px-3">Student Name</th>
                        <th class="py-3 px-3">Parent Name</th>
                        <th class="py-3 px-3">Fee Groups</th>
                        <th class="py-3 px-3">Due Date</th>
                        <th class="py-3 px-3 text-right">Total Amount</th>
                        <th class="py-3 px-3 text-right">Paid Amount</th>
                        <th class="py-3 px-3 text-right">Discount</th>
                        <th class="py-3 px-3 text-right">Due Amount</th>
                        <th class="py-3 px-3 text-right">Late Fine</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dueStudents as $idx => $st)
                    @php
                        $alloc = $st->feeAllocations->first();
                        $tot = $alloc ? $alloc->final_amount : 12500;
                        $discount = $alloc ? $alloc->discount_amount : 0;
                        $paid = $st->feePayments ? $st->feePayments->sum('amount_paid') : 0;
                        $due = max(0, $tot - $paid);
                        $fine = 20.00;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-3">
                            <input type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        </td>
                        <td class="py-3 px-3 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-3 font-mono font-bold text-slate-800">{{ $st->admission_no }}</td>
                        <td class="py-3 px-3 font-bold text-slate-800">
                            {{ $st->first_name }} {{ $st->last_name }}
                            <div class="text-[10px] text-slate-400 font-normal">{{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }}</div>
                        </td>
                        <td class="py-3 px-3 text-slate-600">{{ $st->guardian->father_name ?? 'Parent' }}</td>
                        <td class="py-3 px-3">
                            <span class="bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded text-[10px]">
                                Quarter 3 Composite
                            </span>
                        </td>
                        <td class="py-3 px-3 text-slate-600 font-mono">15 Nov 2026</td>
                        <td class="py-3 px-3 text-right font-medium text-slate-700">₹{{ number_format($tot, 2) }}</td>
                        <td class="py-3 px-3 text-right font-medium text-emerald-600">₹{{ number_format($paid, 2) }}</td>
                        <td class="py-3 px-3 text-right text-slate-500">₹{{ number_format($discount, 2) }}</td>
                        <td class="py-3 px-3 text-right font-bold text-red-600">₹{{ number_format($due, 2) }}</td>
                        <td class="py-3 px-3 text-right text-amber-600 font-semibold">₹{{ number_format($fine, 2) }}</td>
                        <td class="py-3 px-3 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('fees.collect', ['student_id' => $st->id]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2 py-1 rounded text-[10px]">
                                    Pay Now
                                </a>
                                <button onclick="alert('Sending reminder to parent on WhatsApp: +91 98765-43210')" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 p-1 rounded text-xs" title="WhatsApp Reminder">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center py-6 text-slate-400">Zero due records found for this filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


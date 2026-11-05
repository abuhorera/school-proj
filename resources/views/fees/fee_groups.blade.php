@extends('layouts.app')

@section('title', 'Fee Groups Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-layer-group"></i>
                <span>Fee Architecture & Billing Cycles</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fee Groups Master</h1>
            <p class="text-xs text-slate-500 mt-1">Configure Monthly, Quarterly, Term-wise or Annual composite fee packages.</p>
        </div>
        <button onclick="alert('Add Fee Group modal')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Fee Group</span>
        </button>
    </div>

    <!-- Scenarios Cards (Exact match to demo) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Scenario 1</span>
            <h4 class="font-bold text-sm text-slate-800">Monthly Billing Package</h4>
            <p class="text-slate-500">Includes Tuition fee and daily transport billed on the 1st of every calendar month.</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Scenario 2</span>
            <h4 class="font-bold text-sm text-slate-800">Term-wise / Quarterly Group</h4>
            <p class="text-slate-500">Collected in 4 quarterly cycles (Apr, Jul, Oct, Jan) for tuition, exams, and lab resources.</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Scenario 3</span>
            <h4 class="font-bold text-sm text-slate-800">Custom Admission Composite</h4>
            <p class="text-slate-500">One-time registration, security deposit, annual development fund and identity badges.</p>
        </div>
    </div>

    <!-- Fee Groups Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Configured Fee Groups (Total: {{ $groups->count() }})</h3>
            <span class="text-xs text-slate-500">Session 2026-27</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Fee Group Name</th>
                        <th class="py-3 px-4">Fee Types & Included Details</th>
                        <th class="py-3 px-4 text-right">Total Amount (₹)</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($groups as $idx => $fg)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $fg->name }}
                            <div class="text-[10px] text-slate-400 font-normal">Active cycle</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($fg->feeTypes as $ft)
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-medium border border-slate-200">
                                        {{ $ft->name }}
                                    </span>
                                @empty
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px]">Tuition Fee</span>
                                    <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-[10px]">Lab Access</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-slate-800">₹12,500.00</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('fees.assign') }}" class="text-purple-600 hover:text-purple-800 font-semibold mr-2">Assign</a>
                            <button class="text-slate-500 hover:text-slate-800 font-medium">Edit</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


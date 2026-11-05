@extends('layouts.app')

@section('title', 'Late Fine Rules')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-rose-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-clock"></i>
                <span>Automated Penalty Computation</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Late Fine Rules <span class="bg-rose-900/40 text-rose-700 text-xs px-2 py-0.5 rounded font-bold">NEW</span></h1>
            <p class="text-xs text-slate-500 mt-1">Configure grace periods, daily compounding fines, and one-time default penalty slabs for overdue student fees.</p>
        </div>
        <button onclick="alert('Create Fine Rule modal')" class="bg-rose-600 hover:bg-rose-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Fine Rule</span>
        </button>
    </div>

    <!-- Table 1: Rules Master (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Configured Fine Policies</h3>
            <span class="text-xs text-slate-500">Auto-calculated daily at midnight</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Rule Name</th>
                        <th class="py-3 px-4">How it works (Policy Logic)</th>
                        <th class="py-3 px-4">Used by Fee Groups</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rules as $r)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-bold text-slate-800 flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $r->name }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $r->description ?? 'Charges ₹' . $r->amount . ' per day after ' . $r->grace_days . ' days grace period.' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded text-[10px]">
                                All Tuition Fee Groups
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold mr-2">Edit</button>
                            <button class="text-slate-400 hover:text-slate-600 font-medium">Deactivate</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table 2: Active Fine Impact on Current Fee Groups (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Current Overdue Groups & Late Fine Applied Today</h3>
            <span class="text-xs text-rose-600 font-bold bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                Live Calculation
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Fee Group</th>
                        <th class="py-3 px-4">Due Date</th>
                        <th class="py-3 px-4 text-right">Late Fine Today</th>
                        <th class="py-3 px-4 text-right">Students Impacted</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($groups as $idx => $g)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $g->name }}</td>
                        <td class="py-3 px-4 text-slate-600 font-mono">10 Nov 2026</td>
                        <td class="py-3 px-4 text-right font-bold text-rose-600">₹20.00 / day</td>
                        <td class="py-3 px-4 text-right">
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px]">
                                10 Students Overdue
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="alert('Waiver modal opened for {{ $g->name }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-2.5 py-1 rounded text-xs">
                                Waive Fines
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


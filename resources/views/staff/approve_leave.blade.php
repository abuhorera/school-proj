@extends('layouts.app')

@section('title', 'Approve Staff Leaves - Human Resource')

@section('content')
<div class="space-y-6">
    @include('staff.partials.subnav')

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Staff Leave Applications & Approval Workflow</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Review faculty leave submissions, quota deductions, and approve or reject applications</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('staff.leave-types') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-calendar-xmark text-slate-500"></i>
                <span>Configure Leave Types</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('staff.approve-leave') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Application Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">All Applications</option>
                    <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter Leaves</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Leaves Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Staff Leave Ledger ({{ $leaves->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Staff Member</th>
                        <th class="py-3 px-4">Leave Type</th>
                        <th class="py-3 px-4">Leave Duration</th>
                        <th class="py-3 px-4">Days</th>
                        <th class="py-3 px-4">Reason / Notes</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($leaves as $index => $l)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $l->staff->first_name ?? 'Staff' }} {{ $l->staff->last_name ?? '' }}</div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $l->staff->employee_id ?? '' }} • {{ $l->staff->department ?? '' }}</span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            {{ $l->leaveType->name ?? 'General Leave' }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600">
                            {{ $l->from_date ? $l->from_date->format('d M, Y') : '' }} to {{ $l->to_date ? $l->to_date->format('d M, Y') : '' }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $l->days_count }} days
                        </td>
                        <td class="py-3 px-4 text-slate-600 max-w-xs text-[11px]">
                            {{ $l->reason }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $l->status == 'Approved' ? 'bg-emerald-100 text-emerald-800' : ($l->status == 'Rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $l->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($l->status == 'Pending')
                            <div class="flex items-center justify-end space-x-1">
                                <form action="{{ route('staff.approve-leave.update', $l->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] transition">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('staff.approve-leave.update', $l->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-[10px] transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-[11px] text-slate-400 italic">Actioned</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">No leave applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


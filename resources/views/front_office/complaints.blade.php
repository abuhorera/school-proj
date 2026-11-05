@extends('layouts.app')

@section('title', 'Complaints & Grievances - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('front-office.complaints') }}" class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Filter:</span>
            
            <select name="type" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-cyan-500">
                <option value="">-- All Types --</option>
                @foreach($types as $tp)
                    <option value="{{ $tp }}" {{ $typeFilter == $tp ? 'selected' : '' }}>{{ $tp }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-cyan-500">
                <option value="">-- All Statuses --</option>
                <option value="Open" {{ $statusFilter == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress" {{ $statusFilter == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved" {{ $statusFilter == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            </select>

            @if($typeFilter || $statusFilter)
                <a href="{{ route('front-office.complaints') }}" class="text-xs text-rose-600 hover:text-rose-800 font-medium px-2 py-1">
                    Clear Filter
                </a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addComplaintModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Log Complaint</span>
            </button>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-regular fa-comment-dots text-rose-600"></i>
                <span class="text-xs font-bold text-slate-800">Grievance Ticket Tracker ({{ $complaints->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Stakeholder issue resolution & audit</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">Ticket #</th>
                        <th class="p-3.5">Complainant Name</th>
                        <th class="p-3.5">Contact Phone</th>
                        <th class="p-3.5">Category</th>
                        <th class="p-3.5">Date Logged</th>
                        <th class="p-3.5">Assigned To</th>
                        <th class="p-3.5">Details & Action Taken</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $c)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                #{{ $c->id }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                {{ $c->complaint_by }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-700">
                                {{ $c->phone }}
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $c->complaint_type }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ date('d M Y', strtotime($c->date)) }}
                            </td>
                            <td class="p-3.5 text-slate-700">
                                @if($c->assignedStaff)
                                    <span class="font-medium">{{ $c->assignedStaff->first_name }} {{ $c->assignedStaff->last_name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="p-3.5 max-w-xs">
                                <div class="text-xs text-slate-800 line-clamp-2">{{ $c->description }}</div>
                                @if($c->action_taken)
                                    <div class="text-[10px] text-emerald-700 font-medium mt-0.5">
                                        <i class="fa-solid fa-check text-[9px] mr-1"></i>{{ $c->action_taken }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                @if($c->status == 'Resolved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Resolved
                                    </span>
                                @elseif($c->status == 'In Progress')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        In Progress
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        Pending Open
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    @if($c->status != 'Resolved')
                                        <form method="POST" action="{{ route('front-office.complaints.resolve', $c->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Resolved">
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded border border-emerald-200 transition">
                                                Resolve
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('front-office.complaints.delete', $c->id) }}" onsubmit="return confirm('Delete complaint #{{ $c->id }}?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Ticket" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-400">
                                No grievance complaints logged.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Log New Complaint -->
<div id="addComplaintModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-regular fa-comment-dots text-rose-400"></i>
                <span>Log New Grievance / Complaint</span>
            </h4>
            <button onclick="document.getElementById('addComplaintModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.complaints.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Complainant Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="complainant_name" required placeholder="Parent or Staff Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="10-digit number" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Complaint Type <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                        @foreach($types as $tp)
                            <option value="{{ $tp }}">{{ $tp }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date Logged <span class="text-rose-500">*</span></label>
                    <input type="date" name="complaint_date" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assign Staff</label>
                    <select name="assigned_to" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                        <option value="">-- Unassigned --</option>
                        @foreach($staff as $st)
                            <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Detailed Description <span class="text-rose-500">*</span></label>
                <textarea name="description" required rows="3" placeholder="Provide factual context of the issue..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addComplaintModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs transition">
                    Log Grievance Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


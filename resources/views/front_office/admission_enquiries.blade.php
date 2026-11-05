@extends('layouts.app')

@section('title', 'Admission Enquiries - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('front-office.admission-enquiries') }}" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search candidate, phone..." class="text-xs rounded-lg border border-slate-300 pl-8 pr-3 py-1.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-cyan-500">
                <option value="">-- All Statuses --</option>
                <option value="Open" {{ $statusFilter == 'Open' ? 'selected' : '' }}>Open Leads</option>
                <option value="Contacted" {{ $statusFilter == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="Converted" {{ $statusFilter == 'Converted' ? 'selected' : '' }}>Converted</option>
                <option value="Closed" {{ $statusFilter == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-cyan-500">
                <option value="">-- All Classes --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classFilter == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            @if($search || $statusFilter || $classFilter)
                <a href="{{ route('front-office.admission-enquiries') }}" class="text-xs text-rose-600 hover:text-rose-800 font-medium px-2 py-1">
                    Clear
                </a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addEnquiryModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Enquiry</span>
            </button>
            <a href="{{ route('students.create') }}" class="px-3 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-user-plus text-[10px]"></i>
                <span>Direct Admission</span>
            </a>
        </div>
    </div>

    <!-- Enquiries Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-regular fa-clipboard text-cyan-600"></i>
                <span class="text-xs font-bold text-slate-800">Prospective Admission Leads ({{ $enquiries->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Track pipeline from enquiry to enrollment</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Student Name</th>
                        <th class="p-3.5">Parent / Guardian</th>
                        <th class="p-3.5">Phone Number</th>
                        <th class="p-3.5">Class of Interest</th>
                        <th class="p-3.5">Enquiry Date</th>
                        <th class="p-3.5">Follow-up Date</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enquiries as $index => $enq)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-full bg-cyan-50 text-cyan-700 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr($enq->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-xs">{{ $enq->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-normal">Source: {{ $enq->source ?? 'Walk In' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 font-medium text-slate-700">
                                {{ $enq->parent_name ?? 'N/A' }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-700">
                                {{ $enq->phone }}
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $enq->class_applying_for ?? ($enq->schoolClass->name ?? 'General') }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ date('d M Y', strtotime($enq->enquiry_date ?? $enq->created_at)) }}
                            </td>
                            <td class="p-3.5 font-semibold text-indigo-600">
                                {{ $enq->next_follow_up ? date('d M Y', strtotime($enq->next_follow_up)) : '—' }}
                            </td>
                            <td class="p-3.5 text-center">
                                @if($enq->status == 'Converted')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Converted</span>
                                @elseif($enq->status == 'Contacted')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Contacted</span>
                                @elseif($enq->status == 'Closed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-200 text-slate-700">Closed</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Open Lead</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <form method="POST" action="{{ route('front-office.admission-enquiries.delete', $enq->id) }}" onsubmit="return confirm('Delete this enquiry record?');" class="inline">
                                    @csrf
                                    <button type="submit" title="Delete Enquiry" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-400">
                                No admission enquiries found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Admission Enquiry -->
<div id="addEnquiryModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-regular fa-clipboard text-cyan-400"></i>
                <span>Log New Admission Enquiry</span>
            </h4>
            <button onclick="document.getElementById('addEnquiryModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.admission-enquiries.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Student / Candidate Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_name" required placeholder="Full Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Parent / Guardian Name</label>
                    <input type="text" name="parent_name" placeholder="Father or Mother" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile / Phone <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="10-digit number" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Class of Interest</label>
                    <select name="school_class_id" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Lead Source</label>
                    <select name="admission_source" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="Walk In">Walk In</option>
                        <option value="Website">Website</option>
                        <option value="Phone Call">Phone Call</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Referral">Referral</option>
                        <option value="Newspaper Ad">Newspaper Ad</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Enquiry Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="enquiry_date" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Next Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Assigned Counselor / Staff</label>
                <select name="assigned_to" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="">-- Assign Counselor --</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Notes / Discussion Summary</label>
                <textarea name="notes" rows="2" placeholder="Parent requested school tour, fee brochure..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addEnquiryModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition">
                    Save Enquiry
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


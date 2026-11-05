@extends('layouts.app')

@section('title', 'Front Office & Lead Management')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">Front Office & Lead Pipeline</h1>
            </div>
            <p class="text-xs text-slate-500">Manage incoming admission inquiries, visitor logs, complaints, and gate terminals</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- 3 Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Admission Inquiries</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $enquiries->count() }} Leads</div>
            <span class="text-[10px] text-indigo-600 font-semibold">Active Pipeline</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Visitors Inside Campus</span>
            <div class="text-2xl font-black text-cyan-600 mt-1">{{ $visitors->where('status', 'inside')->count() }} Active</div>
            <span class="text-[10px] text-slate-400">Gate Passes Checked In</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Complaints Under Triage</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $complaints->where('status', '!=', 'Resolved')->count() }} Open</div>
            <span class="text-[10px] text-emerald-600 font-semibold">{{ $complaints->where('status', 'Resolved')->count() }} Resolved</span>
        </div>
    </div>

    <!-- Section 1: Admission Enquiries (Lead Pipeline) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-funnel-dollar text-indigo-600"></i>
                <span>Admission Enquiries (Lead Pipeline)</span>
            </h2>
            <span class="text-xs bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full">{{ $enquiries->count() }} Enquiries</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Parent Name</th>
                        <th class="py-2.5 px-3">Phone</th>
                        <th class="py-2.5 px-3">Class Applying</th>
                        <th class="py-2.5 px-3">Source</th>
                        <th class="py-2.5 px-3">Next Follow-up</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($enquiries as $enq)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-2.5 px-3 font-bold text-slate-900">{{ $enq->name }}</td>
                            <td class="py-2.5 px-3 font-mono text-slate-700">{{ $enq->phone }}</td>
                            <td class="py-2.5 px-3"><span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded">{{ $enq->class_applying_for }}</span></td>
                            <td class="py-2.5 px-3 text-slate-600">{{ $enq->source }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-700">{{ $enq->next_follow_up ?? 'Today' }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $enq->status == 'Visit Scheduled' ? 'bg-purple-100 text-purple-800' : ($enq->status == 'New' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $enq->status }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 text-[11px]">{{ $enq->remarks }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-4 text-center text-slate-400 italic">No enquiries recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Visitor Book & Gate Passes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Book -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-book-open-reader text-cyan-600"></i>
                    <span>Visitor Book (Gate Terminal)</span>
                </h2>
                <span class="text-xs bg-cyan-50 text-cyan-700 font-bold px-2 py-0.5 rounded-full">Real-time</span>
            </div>

            <div class="space-y-2">
                @foreach($visitors as $v)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $v->name }} <span class="font-mono text-slate-500 text-[11px]">({{ $v->phone }})</span></div>
                            <div class="text-[11px] text-slate-600">Purpose: {{ $v->purpose }} • To meet: <strong class="text-slate-800">{{ $v->person_to_meet }}</strong></div>
                            <div class="text-[10px] text-slate-400 mt-0.5">In: {{ date('h:i A, d M', strtotime($v->in_time)) }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $v->status == 'inside' ? 'bg-cyan-100 text-cyan-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ $v->status == 'inside' ? 'Inside Campus' : 'Checked Out' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Complaints & Feedback -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-message-exclamation text-amber-500"></i>
                    <span>Complaints & Parent Grievances</span>
                </h2>
                <span class="text-xs bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded-full">Triage</span>
            </div>

            <div class="space-y-2">
                @foreach($complaints as $c)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900">{{ $c->complaint_by }} ({{ $c->complaint_type }})</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $c->status == 'Resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $c->status }}
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px]">{{ $c->description }}</p>
                        @if($c->action_taken)
                            <div class="text-[10px] text-emerald-700 bg-emerald-50 p-1.5 rounded font-medium">
                                <strong>Action Taken:</strong> {{ $c->action_taken }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection


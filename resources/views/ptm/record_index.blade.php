@extends('layouts.app')

@section('title', 'Attendance & Remarks - Parent Teacher Meetings')

@section('content')
<div class="space-y-6">
    @include('ptm.partials.subnav')

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
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-clipboard-user"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">PTM Attendance & Remarks</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Select a conference to mark guardian attendance, write individual student feedback, and rate progress</p>
        </div>
        <a href="{{ route('ptm.meetings.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
            <i class="fa-solid fa-plus"></i>
            <span>Schedule Meeting</span>
        </a>
    </div>

    <!-- Meetings You Can Record Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Meetings You Can Record ({{ $meetings->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Meeting</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Scope</th>
                        <th class="py-3 px-4">Invited</th>
                        <th class="py-3 px-4">Present</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($meetings as $m)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('ptm.meetings.show', $m->id) }}" class="hover:text-amber-600 transition">
                                    {{ $m->title }}
                                </a>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                {{ $m->venue ?? 'School Campus' }}
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-800">
                            {{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4">
                            @if($m->scope == 'whole_school')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700">Whole school</span>
                            @elseif($m->scope == 'class')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $m->schoolClass->name ?? 'Class' }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700">Section</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                            {{ $m->attendances_count }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-emerald-600">
                            {{ $m->present_count }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusColors = [
                                    'Draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'Scheduled' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Notified' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'In progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $color = $statusColors[$m->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $color }}">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('ptm.meetings.record', $m->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition inline-flex items-center space-x-1 shadow-xs">
                                <i class="fa-solid fa-clipboard-user text-[10px]"></i>
                                <span>Record</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No meetings available for recording.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'PTM Dashboard - Parent Teacher Meetings')

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
                    <i class="fa-solid fa-handshake-angle"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Parent–Teacher Meetings</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Schedule PTMs, invite guardians, record attendance & remarks, and track post-meeting follow-ups</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('ptm.meetings.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Schedule Meeting</span>
            </a>
            <a href="{{ route('ptm.record') }}" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-clipboard-user text-amber-500"></i>
                <span>Record Remarks</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Meetings This Session</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $meetingsThisSession }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $meetingsThisSession }} total all-time</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Upcoming Meetings</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ $upcomingMeetings }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $completedMeetings }} completed</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Open Follow-ups</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1 font-mono">{{ $openFollowups }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $inProgressFollowups }} in progress</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Guardian Turnout Rate</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ $attendanceRate }}%</h3>
                <p class="text-[11px] text-emerald-600 mt-0.5">Verified attendance</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Upcoming Meetings Banner -->
    @if($upcomingList->count() > 0)
    <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600 rounded-2xl shadow-sm p-5 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-bold tracking-wider uppercase mb-2">
                    <i class="fa-solid fa-bell"></i>
                    <span>Next Scheduled PTM</span>
                </div>
                @php $nextM = $upcomingList->first(); @endphp
                <h2 class="text-base font-black">{{ $nextM->title }}</h2>
                <p class="text-xs text-amber-100 mt-1">
                    <i class="fa-solid fa-calendar-day mr-1"></i> {{ \Carbon\Carbon::parse($nextM->meeting_date)->format('l, d F Y') }} &nbsp;•&nbsp; 
                    <i class="fa-solid fa-clock mr-1"></i> {{ $nextM->start_time ?? '09:00' }} - {{ $nextM->end_time ?? '13:00' }} &nbsp;•&nbsp;
                    <i class="fa-solid fa-location-dot mr-1"></i> {{ $nextM->venue ?? 'School Campus' }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('ptm.meetings.show', $nextM->id) }}" class="px-4 py-2 bg-white text-amber-700 hover:bg-amber-50 rounded-xl text-xs font-bold transition shadow-xs">
                    View Guardian Roster
                </a>
                <a href="{{ route('ptm.meetings.record', $nextM->id) }}" class="px-4 py-2 bg-slate-900/40 hover:bg-slate-900/60 text-white border border-white/20 rounded-xl text-xs font-bold transition">
                    Record Attendance
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Meetings Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Recent & Active PTM Meetings</h3>
                <p class="text-xs text-slate-400 mt-0.5">Overview of conferences, attendance status, and parent turnout</p>
            </div>
            <a href="{{ route('ptm.meetings') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center space-x-1">
                <span>View All Meetings</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Meeting</th>
                        <th class="py-3 px-4">Date / Time</th>
                        <th class="py-3 px-4">Scope</th>
                        <th class="py-3 px-4">Invited</th>
                        <th class="py-3 px-4">Present</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentMeetings as $m)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('ptm.meetings.show', $m->id) }}" class="hover:text-amber-600 transition">
                                    {{ $m->title }}
                                </a>
                            </div>
                            <div class="flex items-center space-x-2 mt-0.5 text-[10px] text-slate-400">
                                <span class="capitalize"><i class="fa-solid fa-video text-slate-400 mr-1"></i>{{ str_replace('_', ' ', $m->mode) }}</span>
                                <span>•</span>
                                <span class="truncate max-w-xs">{{ $m->venue ?? 'School Campus' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px]">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $m->start_time ?? '09:00' }} - {{ $m->end_time ?? '13:00' }}</div>
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
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('ptm.meetings.show', $m->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="View Details">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('ptm.meetings.record', $m->id) }}" class="px-2.5 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg text-[10px] font-bold transition flex items-center space-x-1">
                                    <i class="fa-solid fa-clipboard-user text-[9px]"></i>
                                    <span>Record</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No parent-teacher meetings scheduled yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


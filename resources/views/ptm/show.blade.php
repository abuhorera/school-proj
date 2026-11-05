@extends('layouts.app')

@section('title', 'PTM — ' . $meeting->title)

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

    <!-- Meeting Header Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-handshake"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">{{ $meeting->title }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    {{ $meeting->status }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-3">
                <span><i class="fa-solid fa-calendar-day mr-1 text-slate-400"></i>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('l, d F Y') }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-clock mr-1 text-slate-400"></i>{{ $meeting->start_time ?? '09:00' }} - {{ $meeting->end_time ?? '13:00' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i>{{ $meeting->venue ?? 'School Campus' }}</span>
                <span>•</span>
                <span class="capitalize"><i class="fa-solid fa-video mr-1 text-slate-400"></i>{{ str_replace('_', ' ', $meeting->mode) }}</span>
            </p>
            @if($meeting->description)
                <p class="text-[11px] text-slate-500 mt-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    {{ $meeting->description }}
                </p>
            @endif
        </div>
        <div class="flex items-center space-x-2.5">
            @if($meeting->status != 'Notified' && $meeting->status != 'Completed')
            <form action="{{ route('ptm.meetings.notify', $meeting->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-2 bg-sky-50 text-sky-700 hover:bg-sky-100 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-paper-plane text-[10px]"></i>
                    <span>Broadcast Invites</span>
                </button>
            </form>
            @endif
            <a href="{{ route('ptm.meetings.record', $meeting->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-clipboard-user text-[11px]"></i>
                <span>Record Remarks</span>
            </a>
            <a href="{{ route('ptm.meetings.edit', $meeting->id) }}" class="p-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl" title="Edit Conference">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
            </a>
        </div>
    </div>

    <!-- 5 KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase">Total Invited</span>
            <h3 class="text-xl font-black text-slate-800 mt-1 font-mono">{{ $totalInvited }}</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Guardians scheduled</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase">Present</span>
            <h3 class="text-xl font-black text-emerald-600 mt-1 font-mono">{{ $presentCount }}</h3>
            <p class="text-[10px] text-emerald-600 mt-0.5">Attended</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-amber-600 uppercase">Pending</span>
            <h3 class="text-xl font-black text-amber-600 mt-1 font-mono">{{ $pendingCount }}</h3>
            <p class="text-[10px] text-amber-600 mt-0.5">Awaiting check-in</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-rose-600 uppercase">Absent</span>
            <h3 class="text-xl font-black text-rose-600 mt-1 font-mono">{{ $absentCount }}</h3>
            <p class="text-[10px] text-rose-500 mt-0.5">Did not attend</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-3.5 shadow-xs">
            <span class="text-[10px] font-bold text-purple-600 uppercase">Follow-ups</span>
            <h3 class="text-xl font-black text-purple-600 mt-1 font-mono">{{ $followupsCount }}</h3>
            <p class="text-[10px] text-purple-500 mt-0.5">Action items</p>
        </div>
    </div>

    <!-- Guardian Roster Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Guardian Roster ({{ $meeting->attendances->count() }})</h3>
            <span class="text-xs text-slate-400 font-mono">15-minute slot allocations</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Adm / Roll</th>
                        <th class="py-3 px-4">Guardian</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4">Slot</th>
                        <th class="py-3 px-4">Notified</th>
                        <th class="py-3 px-4">Attendance</th>
                        <th class="py-3 px-4 text-right">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($meeting->attendances as $index => $att)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            {{ $att->student->first_name ?? 'Student' }} {{ $att->student->last_name ?? '' }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                            {{ $att->student->admission_no ?? 'YIS-00' }} / {{ $att->student->roll_no ?? '-' }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            {{ $att->student->guardian->name ?? ($att->student->father_name ?? 'Guardian') }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                            {{ $att->student->emergency_contact ?? ($att->student->phone ?? '9876500000') }}
                        </td>
                        <td class="py-3 px-4 font-mono text-[10px] text-amber-700 font-semibold bg-amber-50/50 rounded">
                            {{ $att->slot_time ?? '09:00 AM - 09:15 AM' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($att->is_notified)
                                <span class="text-emerald-600 font-bold text-[10px]"><i class="fa-solid fa-circle-check mr-0.5"></i> Sent</span>
                            @else
                                <span class="text-slate-400 text-[10px]">Pending</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($att->attendance_status == 'Present')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Present</span>
                            @elseif($att->attendance_status == 'Absent')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Absent</span>
                            @elseif($att->attendance_status == 'Rescheduled')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Rescheduled</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($att->performance_remark || $att->overall_rating)
                                <span class="text-[11px] font-bold text-amber-600">
                                    ★ {{ $att->overall_rating ?? '4' }}/5
                                </span>
                            @else
                                <span class="text-[10px] text-slate-400 italic">No remark</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">No students allocated to this meeting roster.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


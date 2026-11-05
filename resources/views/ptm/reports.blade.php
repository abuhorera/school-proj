@extends('layouts.app')

@section('title', 'PTM Reports - Parent Teacher Meetings')

@section('content')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableReport, #printableReport * {
        visibility: visible;
    }
    #printableReport {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 16px;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<div class="space-y-6">
    <div class="no-print">
        @include('ptm.partials.subnav')
    </div>

    <!-- Header bar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">PTM Reports & Analytics</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Class-wise summary and student-wise breakdown, with printable report export</p>
        </div>
        @if($selectedMeeting)
        <div>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-print"></i>
                <span>Print / Export PDF</span>
            </button>
        </div>
        @endif
    </div>

    <!-- Meeting Selection Form Toolbar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('ptm.reports') }}" class="flex flex-col sm:flex-row sm:items-center gap-3">
            <label class="text-xs font-bold text-slate-600 whitespace-nowrap">
                <i class="fa-solid fa-calendar-check text-amber-500 mr-1.5"></i>Select a meeting:
            </label>
            <select name="meeting_id" onchange="this.form.submit()" class="text-xs rounded-xl border border-slate-200 px-3.5 py-2 focus:ring-2 focus:ring-amber-500 bg-slate-50 font-semibold text-slate-800 flex-1 max-w-lg">
                <option value="">-- Choose a PTM --</option>
                @foreach($meetings as $m)
                    <option value="{{ $m->id }}" {{ $meetingId == $m->id ? 'selected' : '' }}>
                        {{ $m->title }} ({{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }}) — {{ $m->status }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if(!$selectedMeeting)
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center text-slate-400">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-3 text-2xl">
            <i class="fa-solid fa-handshake"></i>
        </div>
        <h3 class="font-bold text-slate-800 text-sm">Select a meeting to view its report</h3>
        <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">Class-wise summary, parent attendance rate, star rating distributions, and student-wise feedback log.</p>
    </div>
    @else

    <!-- Printable Report Section -->
    <div id="printableReport" class="space-y-6">
        <!-- Report Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">{{ $school->name ?? 'Greenwood High School' }}</span>
                    <h2 class="text-lg font-black text-slate-900 mt-0.5">{{ $selectedMeeting->title }}</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        <i class="fa-solid fa-calendar mr-1"></i> Date: {{ \Carbon\Carbon::parse($selectedMeeting->meeting_date)->format('d F Y') }} &nbsp;•&nbsp; 
                        <i class="fa-solid fa-clock mr-1"></i> Time: {{ $selectedMeeting->start_time ?? '09:00' }} - {{ $selectedMeeting->end_time ?? '13:00' }} &nbsp;•&nbsp;
                        <i class="fa-solid fa-location-dot mr-1"></i> Venue: {{ $selectedMeeting->venue ?? 'School Campus' }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $selectedMeeting->status }}
                    </span>
                    <p class="text-[10px] text-slate-400 font-mono mt-1">Scope: {{ ucfirst(str_replace('_', ' ', $selectedMeeting->scope)) }}</p>
                </div>
            </div>

            <!-- Executive KPI Metrics -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 pt-4">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Invited</span>
                    <div class="text-lg font-black text-slate-800 font-mono mt-0.5">{{ $summary['total'] }}</div>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <span class="text-[9px] font-bold text-emerald-600 uppercase">Turnout</span>
                    <div class="text-lg font-black text-emerald-600 font-mono mt-0.5">{{ $summary['turnout_rate'] }}%</div>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <span class="text-[9px] font-bold text-emerald-600 uppercase">Present</span>
                    <div class="text-lg font-black text-emerald-600 font-mono mt-0.5">{{ $summary['present'] }}</div>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl">
                    <span class="text-[9px] font-bold text-rose-600 uppercase">Absent</span>
                    <div class="text-lg font-black text-rose-600 font-mono mt-0.5">{{ $summary['absent'] }}</div>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <span class="text-[9px] font-bold text-purple-600 uppercase">Rescheduled</span>
                    <div class="text-lg font-black text-purple-600 font-mono mt-0.5">{{ $summary['rescheduled'] }}</div>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <span class="text-[9px] font-bold text-amber-600 uppercase">Avg Rating</span>
                    <div class="text-lg font-black text-amber-600 font-mono mt-0.5">★ {{ $summary['avg_rating'] }} / 5</div>
                </div>
            </div>
        </div>

        <!-- Student-Wise Breakdown Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-xs">Student-Wise Evaluation & Remarks ({{ $selectedMeeting->attendances->count() }})</h3>
                <span class="text-[10px] text-slate-400 font-mono">Verified Records</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">#</th>
                            <th class="py-2.5 px-3">Student Name</th>
                            <th class="py-2.5 px-3">Adm / Roll</th>
                            <th class="py-2.5 px-3">Guardian</th>
                            <th class="py-2.5 px-3">Attendance</th>
                            <th class="py-2.5 px-3">Academic Performance</th>
                            <th class="py-2.5 px-3">Behaviour & Conduct</th>
                            <th class="py-2.5 px-3 text-right">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($selectedMeeting->attendances as $index => $att)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-3 font-mono text-slate-400 text-[10px]">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-3 font-bold text-slate-900 text-xs">
                                {{ $att->student->first_name ?? 'Student' }} {{ $att->student->last_name ?? '' }}
                            </td>
                            <td class="py-2.5 px-3 font-mono text-[10px] text-slate-500">
                                {{ $att->student->admission_no ?? 'YIS-00' }} / {{ $att->student->roll_no ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-800 text-[11px]">
                                {{ $att->student->guardian->name ?? ($att->student->father_name ?? 'Parent') }}
                            </td>
                            <td class="py-2.5 px-3">
                                @if($att->attendance_status == 'Present')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Present</span>
                                @elseif($att->attendance_status == 'Absent')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Absent</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $att->attendance_status }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-[11px] text-slate-600 max-w-xs">
                                {{ $att->performance_remark ?? '—' }}
                            </td>
                            <td class="py-2.5 px-3 text-[11px] text-slate-600 max-w-xs">
                                {{ $att->behaviour_remark ?? '—' }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-bold text-amber-600 text-xs font-mono">
                                {{ $att->overall_rating ? ('★ ' . $att->overall_rating . '/5') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">No attendance records found for this meeting.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection


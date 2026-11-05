@extends('layouts.app')

@section('title', 'Front Office Command Dashboard - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- KPI Metric Cards Strip -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Enquiries -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-cyan-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Enquiries</span>
                <span class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-regular fa-clipboard"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalEnquiries }}</span>
                <span class="text-[10px] text-cyan-600 font-semibold">{{ $openEnquiries }} open</span>
            </div>
            <a href="{{ route('front-office.admission-enquiries') }}" class="mt-2 text-[11px] font-medium text-cyan-600 hover:text-cyan-800 flex items-center space-x-1">
                <span>View pipeline</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Visitors -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Today's Visitors</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-address-book"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $todayVisitors }}</span>
                <span class="text-[10px] text-emerald-600 font-semibold">{{ $activeVisitorsOnCampus }} on campus</span>
            </div>
            <a href="{{ route('front-office.visitor-logs') }}" class="mt-2 text-[11px] font-medium text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                <span>Visitor book</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Gate Passes -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-indigo-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Gate Passes</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-id-badge"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalGatePassesToday }}</span>
                <span class="text-[10px] text-slate-400">today</span>
            </div>
            <a href="{{ route('front-office.gate-passes') }}" class="mt-2 text-[11px] font-medium text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
                <span>View passes</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Campus Workers -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-amber-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Campus Workers</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-hard-hat"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $workersOnCampus }}</span>
                <span class="text-[10px] text-amber-600 font-semibold">on site</span>
            </div>
            <a href="{{ route('front-office.campus-workers') }}" class="mt-2 text-[11px] font-medium text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                <span>Workers registry</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Complaints -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-rose-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Grievances</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-regular fa-comment-dots"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $pendingComplaints }}</span>
                <span class="text-[10px] text-rose-600 font-semibold">pending</span>
            </div>
            <a href="{{ route('front-office.complaints') }}" class="mt-2 text-[11px] font-medium text-rose-600 hover:text-rose-800 flex items-center space-x-1">
                <span>Resolve tickets</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <!-- Postal -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-purple-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Postal Dispatch</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalPostalRecords }}</span>
                <span class="text-[10px] text-slate-400">couriers</span>
            </div>
            <a href="{{ route('front-office.postal-records') }}" class="mt-2 text-[11px] font-medium text-purple-600 hover:text-purple-800 flex items-center space-x-1">
                <span>Mail register</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>

    <!-- Main Grid: Recent Enquiries & Visitors (Left 8 cols) + Gate Terminal & Actions (Right 4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Recent Admission Enquiries Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fa-regular fa-clipboard text-cyan-600"></i>
                            <span>Recent Admission Enquiries</span>
                        </h3>
                        <p class="text-xs text-slate-500">Prospective student walk-ins and phone leads</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('front-office.admission-enquiries') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800">
                            View All Enquiries &rarr;
                        </a>
                        <a href="{{ route('front-office.admission-enquiries.create') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition flex items-center space-x-1">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>New Enquiry</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">STUDENT NAME</th>
                                <th class="p-3.5">PHONE</th>
                                <th class="p-3.5">DATE</th>
                                <th class="p-3.5">NEXT FOLLOW-UP</th>
                                <th class="p-3.5 text-center">STATUS</th>
                                <th class="p-3.5 text-right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentEnquiries as $enq)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3.5 font-bold text-slate-800">
                                        <div class="flex items-center space-x-2.5">
                                            <div class="w-7 h-7 rounded-full bg-cyan-50 text-cyan-700 font-bold text-[10px] flex items-center justify-center">
                                                {{ substr($enq->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-xs">{{ $enq->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-normal">Class: {{ $enq->class_applying_for ?? ($enq->schoolClass->name ?? 'N/A') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3.5 font-mono text-slate-700">
                                        {{ $enq->phone }}
                                    </td>
                                    <td class="p-3.5 text-slate-500">
                                        {{ date('d M Y', strtotime($enq->enquiry_date ?? $enq->created_at)) }}
                                    </td>
                                    <td class="p-3.5 font-semibold text-indigo-600">
                                        {{ $enq->next_follow_up ? date('d M Y', strtotime($enq->next_follow_up)) : 'None scheduled' }}
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
                                        <a href="{{ route('front-office.admission-enquiries') }}" class="text-xs text-cyan-600 hover:text-cyan-800 font-medium">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400">
                                        No recent admission enquiries logged.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Visitors Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-address-book text-emerald-600"></i>
                            <span>Recent Visitors Book</span>
                        </h3>
                        <p class="text-xs text-slate-500">Security checkpoint check-ins and check-outs</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('front-office.visitor-logs') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">
                            Full Visitor Log &rarr;
                        </a>
                        <a href="{{ route('front-office.visitor-logs.create') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition flex items-center space-x-1">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Log Visitor</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">VISITOR NAME</th>
                                <th class="p-3.5">PHONE</th>
                                <th class="p-3.5">PURPOSE</th>
                                <th class="p-3.5">PERSON TO MEET</th>
                                <th class="p-3.5">TIME (IN/OUT)</th>
                                <th class="p-3.5 text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentVisitors as $vis)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3.5 font-bold text-slate-800">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2 h-2 rounded-full {{ $vis->out_time ? 'bg-slate-300' : 'bg-emerald-500 animate-pulse' }}"></span>
                                            <span>{{ $vis->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3.5 font-mono text-slate-700">
                                        {{ $vis->phone }}
                                    </td>
                                    <td class="p-3.5 font-medium text-slate-700">
                                        {{ $vis->purpose }}
                                    </td>
                                    <td class="p-3.5 text-slate-600">
                                        {{ $vis->person_to_meet }}
                                    </td>
                                    <td class="p-3.5">
                                        <div class="flex items-center space-x-1.5 text-[11px]">
                                            <span class="text-emerald-700 font-semibold">{{ date('h:i A', strtotime($vis->in_time)) }}</span>
                                            <span class="text-slate-400">&rarr;</span>
                                            @if($vis->out_time)
                                                <span class="text-slate-600 font-medium">{{ date('h:i A', strtotime($vis->out_time)) }}</span>
                                            @else
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">ON SITE</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        @if(!$vis->out_time)
                                            <form method="POST" action="{{ route('front-office.visitor-logs.out', $vis->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded border border-rose-200 transition">
                                                    Mark Out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-400 italic">Checked Out</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400">
                                        No visitors checked in today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Gate Terminal Fast Pass Scanner Card -->
            <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white rounded-xl p-5 shadow-xs">
                <div class="flex items-center space-x-2 text-emerald-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Gate Terminal Fast Action</span>
                </div>
                <h4 class="text-base font-bold text-white mb-2">Campus Gate Pass Verification</h4>
                <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                    Scan RFID barcode pass or type pass number for instant student early checkout authorization.
                </p>

                <form method="GET" action="{{ route('front-office.gate-terminal') }}" class="space-y-3">
                    <div class="relative">
                        <i class="fa-solid fa-barcode absolute left-3 top-3 text-slate-400 text-sm"></i>
                        <input type="text" name="pass" required placeholder="Scan or enter Pass # (e.g. GP-2026-101)" class="w-full bg-slate-800/90 text-white text-xs rounded-lg pl-9 pr-3 py-2.5 border border-slate-700 focus:outline-none focus:border-emerald-500 font-mono">
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition flex items-center justify-center space-x-1.5 shadow-xs">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                            <span>Lookup Gate Pass</span>
                        </button>
                        <a href="{{ route('front-office.gate-terminal') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-lg border border-slate-700 transition">
                            Terminal
                        </a>
                    </div>
                </form>

                <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Main Gate 1 Active</span>
                    <span class="text-emerald-400 font-semibold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Terminal Online</span>
                    </span>
                </div>
            </div>

            <!-- Quick Action Links Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <h4 class="text-sm font-bold text-slate-800 mb-3">Front Desk Shortcuts</h4>
                <div class="space-y-2">
                    <a href="{{ route('front-office.gate-passes.create') }}" class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/40 text-xs font-medium text-slate-700 transition">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center text-[11px]">
                                <i class="fa-solid fa-id-badge"></i>
                            </span>
                            <span>Issue Student Gate Pass</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                    </a>

                    <a href="{{ route('front-office.campus-workers.create') }}" class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 hover:border-amber-300 hover:bg-amber-50/40 text-xs font-medium text-slate-700 transition">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded bg-amber-50 text-amber-600 flex items-center justify-center text-[11px]">
                                <i class="fa-solid fa-hard-hat"></i>
                            </span>
                            <span>Register Campus Worker</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                    </a>

                    <a href="{{ route('front-office.complaints.create') }}" class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 hover:border-rose-300 hover:bg-rose-50/40 text-xs font-medium text-slate-700 transition">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded bg-rose-50 text-rose-600 flex items-center justify-center text-[11px]">
                                <i class="fa-regular fa-comment-dots"></i>
                            </span>
                            <span>Log Grievance Ticket</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                    </a>

                    <a href="{{ route('front-office.postal-records.create') }}" class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/40 text-xs font-medium text-slate-700 transition">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded bg-purple-50 text-purple-600 flex items-center justify-center text-[11px]">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </span>
                            <span>Record Postal / Courier</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                    </a>
                </div>
            </div>

            <!-- Campus Workers On-Site Mini List -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-bold text-slate-800">Workers On Campus</h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                        {{ $workersOnCampus }} Checked In
                    </span>
                </div>
                <div class="space-y-2">
                    <div class="text-[11px] text-slate-500">
                        Active maintenance and security personnel currently inside school premises.
                    </div>
                    <a href="{{ route('front-office.campus-workers', ['filter' => 'on_campus']) }}" class="block text-center text-xs font-semibold text-amber-700 hover:text-amber-900 pt-2 border-t border-slate-100">
                        View On-Campus Workers &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


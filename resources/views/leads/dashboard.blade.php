@extends('layouts.app')

@section('title', 'Lead Management & Admissions CRM')

@section('content')
<div class="space-y-6">
    @include('leads.partials.subnav')

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

    <!-- Top KPI Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Leads -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Leads</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($totalLeads) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Pipeline Inquiries</span>
                <span class="text-cyan-700 font-bold bg-cyan-50 px-2 py-0.5 rounded-full text-[10px]">ALL TIME</span>
            </div>
        </div>

        <!-- 2. Active in Pipeline -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Active Pipeline</span>
                    <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($activeLeads) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-arrows-spin"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">In Progress</span>
                <span class="text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-full text-[10px]">CURRENT</span>
            </div>
        </div>

        <!-- 3. Enrolled / Converted -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Enrolled / Admitted</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($convertedLeads) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Win Rate</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full text-[10px]">{{ $conversionRate }}% Conv.</span>
            </div>
        </div>

        <!-- 4. Lost / Rejected -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Lost / Dropped</span>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($lostLeads) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Inactive</span>
                <span class="text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-full text-[10px]">{{ $totalLeads > 0 ? round(($lostLeads / $totalLeads)*100, 1) : 0 }}% Drop</span>
            </div>
        </div>
    </div>

    <!-- Admissions Pipeline Stages Funnel Section -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Admissions Pipeline Funnel Overview</h4>
                <p class="text-slate-400 text-xs mt-0.5">Live progression of enquiries across all 9 standard conversion stages</p>
            </div>
            <a href="{{ route('leads.board') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800 transition flex items-center space-x-1">
                <span>Open Kanban Board</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-9 gap-3">
                @foreach($stages as $stage)
                @php
                    $pct = $totalLeads > 0 ? round(($stage->leads_count / $totalLeads) * 100, 1) : 0;
                @endphp
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center relative overflow-hidden group hover:border-slate-300 transition">
                    <div class="w-full h-1 absolute top-0 left-0" style="background-color: {{ $stage->color }}"></div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block truncate" title="{{ $stage->name }}">{{ $stage->name }}</span>
                    <p class="text-xl font-black text-slate-800 mt-2">{{ $stage->leads_count }}</p>
                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $pct }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2 Column Analytics & Operational Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Actionable Follow-ups (Due Today & Overdue) (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Overdue Follow-ups -->
            @if($overdueFollowups->count() > 0)
            <div class="bg-white rounded-2xl border border-rose-100 shadow-xs overflow-hidden">
                <div class="p-4 bg-rose-50/60 border-b border-rose-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
                        <h4 class="font-bold text-rose-900 text-xs uppercase tracking-wider">Overdue Follow-ups Require Immediate Attention ({{ $overdueFollowups->count() }})</h4>
                    </div>
                    <a href="{{ route('leads.tasks') }}" class="text-[11px] font-bold text-rose-700 hover:text-rose-900">View All Tasks &rarr;</a>
                </div>
                <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                    @foreach($overdueFollowups as $od)
                    <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800">{{ $od->student_name }} <span class="text-slate-400 font-normal">({{ $od->class_name ?? 'Class N/A' }})</span></h5>
                                <p class="text-slate-500 text-[11px]">Parent: {{ $od->parent_name }} • <a href="tel:{{ $od->phone }}" class="text-cyan-600 font-medium hover:underline">{{ $od->phone }}</a></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">Due: {{ \Carbon\Carbon::parse($od->follow_up_date)->format('d M') }}</span>
                            <div class="mt-1">
                                <a href="{{ route('leads.tasks') }}" class="text-[10px] text-cyan-600 font-semibold hover:underline">Log Call</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Today's Scheduled Follow-ups -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-check text-cyan-600 text-sm"></i>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Today's Scheduled Follow-ups ({{ $todayFollowups->count() }})</h4>
                    </div>
                    <a href="{{ route('leads.tasks') }}" class="text-[11px] font-bold text-cyan-600 hover:text-cyan-800">Task Schedule &rarr;</a>
                </div>
                @if($todayFollowups->count() == 0)
                <div class="p-8 text-center text-slate-400 text-xs">
                    <i class="fa-regular fa-calendar-check text-3xl mb-2 text-slate-300"></i>
                    <p>No follow-ups due for today! All calls are up to date.</p>
                </div>
                @else
                <div class="divide-y divide-slate-100">
                    @foreach($todayFollowups as $td)
                    <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800">{{ $td->student_name }} <span class="text-slate-400 font-normal">({{ $td->class_name ?? 'Class N/A' }})</span></h5>
                                <p class="text-slate-500 text-[11px]">Parent: {{ $td->parent_name }} • <a href="tel:{{ $td->phone }}" class="text-cyan-600 font-medium hover:underline">{{ $td->phone }}</a></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700" style="border-left: 3px solid {{ $td->stage ? $td->stage->color : '#94a3b8' }}">
                                {{ $td->stage ? $td->stage->name : 'New' }}
                            </span>
                            <a href="{{ route('leads.tasks') }}" class="px-2.5 py-1 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 rounded text-[11px] font-semibold transition">
                                Call / Note
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Recent Enquiries Master Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Recent Enquiries & Registrations</h4>
                    <a href="{{ route('leads.list') }}" class="text-[11px] font-bold text-cyan-600 hover:text-cyan-800">View All Leads ({{ $totalLeads }}) &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="p-3">Candidate / Student</th>
                                <th class="p-3">Class</th>
                                <th class="p-3">Source</th>
                                <th class="p-3">Stage</th>
                                <th class="p-3">Assigned Staff</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentLeads as $lead)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3">
                                    <div class="font-bold text-slate-800">{{ $lead->student_name }}</div>
                                    <div class="text-[11px] text-slate-400">Parent: {{ $lead->parent_name }} • {{ $lead->phone }}</div>
                                </td>
                                <td class="p-3 text-slate-700 font-medium">{{ $lead->class_name ?? '—' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">{{ $lead->source_name ?? 'Direct' }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: {{ $lead->stage ? $lead->stage->color : '#64748b' }}">
                                        {{ $lead->stage ? $lead->stage->name : 'New' }}
                                    </span>
                                </td>
                                <td class="p-3 text-slate-600 text-[11px]">{{ $lead->assignedStaff ? $lead->assignedStaff->name : 'Unassigned' }}</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('leads.list', ['search' => $lead->phone]) }}" class="text-cyan-600 font-bold hover:underline text-[11px]">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Source Channels Breakdown & Counselor Workload (1 col) -->
        <div class="space-y-6">
            <!-- Lead Sources Breakdown -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Leads by Channel Source</h4>
                    <span class="text-[10px] font-bold text-slate-400">{{ $sources->count() }} CHANNELS</span>
                </div>
                <div class="space-y-3">
                    @foreach($sources as $src)
                    @php
                        $srcPct = $totalLeads > 0 ? round(($src->leads_count / $totalLeads) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-slate-700 font-medium">{{ $src->name }}</span>
                            <span class="font-bold text-slate-800">{{ $src->leads_count }} <span class="text-slate-400 text-[10px]">({{ $srcPct }}%)</span></span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-cyan-500 to-indigo-600 h-2 rounded-full" style="width: {{ $srcPct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('leads.settings') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800 flex items-center justify-between">
                        <span>Configure Source Channels</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Counselors / Admissions Officers Workload -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Admissions Counselor Workload</h4>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">ACTIVE</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($counselors as $counselor)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                {{ substr($counselor->name, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-800 block">{{ $counselor->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $counselor->designation ?? 'Counselor' }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700">
                            {{ $counselor->assigned_leads_count }} Leads
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Action Box -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-xs">
                <div class="flex items-center space-x-2 text-cyan-400 mb-2">
                    <i class="fa-solid fa-lightbulb"></i>
                    <h5 class="text-xs font-bold uppercase tracking-wider">Quick Admissions Tip</h5>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Prompt follow-ups within 24 hours of enquiry registration increase final student enrollment rates by over 38%. Use the Kanban Pipeline to move candidates through verification and entrance tests.
                </p>
                <button onclick="document.getElementById('newLeadModal').classList.remove('hidden')" class="mt-4 w-full py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl font-bold text-xs transition cursor-pointer flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Log New Admission Inquiry</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

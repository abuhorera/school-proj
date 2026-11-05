@extends('layouts.app')

@section('title', 'Lesson Planner Dashboard - Curriculum & Syllabus')

@section('content')
<div class="space-y-6">
    @include('lesson_plans.partials.subnav')

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
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-book-reader"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Lesson Planner</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Weekly curriculum preparation, HOD & Principal approval routing, and periodic syllabus coverage tracking</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('lesson-plans.plans.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>New Lesson Plan</span>
            </a>
            <a href="{{ route('lesson-plans.reports') }}" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-chart-column text-emerald-600"></i>
                <span>Full Report</span>
            </a>
        </div>
    </div>

    <!-- 5 KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Plans</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalPlans }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">All sessions</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Drafts</span>
            <h3 class="text-2xl font-black text-slate-700 mt-1 font-mono">{{ $draftsCount }}</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">In progress</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-xs">
            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Pending Review</span>
            <h3 class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ $pendingReviewCount }}</h3>
            <p class="text-[11px] text-amber-600 mt-0.5">Awaiting sign-off</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Approved</span>
            <h3 class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ $approvedCount }}</h3>
            <p class="text-[11px] text-emerald-600 mt-0.5">Active curriculum</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-xs">
            <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">Needs Revision</span>
            <h3 class="text-2xl font-black text-rose-600 mt-1 font-mono">{{ $revisionCount }}</h3>
            <p class="text-[11px] text-rose-500 mt-0.5">Changes requested</p>
        </div>
    </div>

    <!-- Syllabus Coverage Progress Banner -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1.5 flex-1">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-800">Overall Syllabus Completion Rate</span>
                <span class="font-mono font-black text-emerald-600">{{ round($overallCoverageRate, 1) }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $overallCoverageRate)) }}%"></div>
            </div>
            <p class="text-[11px] text-slate-400">Reconciled periodic syllabus covered vs planned across approved academic plans</p>
        </div>
        <div>
            <a href="{{ route('lesson-plans.coverage') }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-bars-progress text-[11px]"></i>
                <span>Reconcile Coverage</span>
            </a>
        </div>
    </div>

    <!-- Recent Lesson Plans Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Recent Weekly Lesson Plans</h3>
                <p class="text-xs text-slate-400 mt-0.5">Teacher lesson planning, week periods, and approval status</p>
            </div>
            <a href="{{ route('lesson-plans.plans') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center space-x-1">
                <span>View All Plans</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Plan</th>
                        <th class="py-3 px-4">Teacher</th>
                        <th class="py-3 px-4">Week</th>
                        <th class="py-3 px-4">Topics</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentPlans as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="hover:text-emerald-600 transition">
                                    {{ $p->title }}
                                </a>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                {{ $p->schoolClass->name ?? 'Class' }} @if($p->section) • Sec {{ $p->section->name }} @endif • {{ $p->subject->name ?? 'Subject' }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-800">{{ $p->staff->first_name ?? 'Faculty' }} {{ $p->staff->last_name ?? '' }}</span>
                            <div class="text-[10px] text-slate-400">{{ $p->staff->department ?? 'Academic' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-700">
                            <div>Week {{ $p->week_number }}</div>
                            <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($p->week_start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($p->week_end_date)->format('d M Y') }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $p->topics_count }} {{ Str::plural('Topic', $p->topics_count) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusStyles = [
                                    'Draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'Pending HOD' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Pending Principal' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Needs revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $style = $statusStyles[$p->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $style }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="View Plan">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('lesson-plans.plans.edit', $p->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="Edit Plan">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No lesson plans created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


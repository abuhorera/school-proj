@extends('layouts.app')

@section('title', 'Lesson Plan Reports - Curriculum & Syllabus')

@section('content')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableLessonReport, #printableLessonReport * {
        visibility: visible;
    }
    #printableLessonReport {
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
        @include('lesson_plans.partials.subnav')
    </div>

    <!-- Header bar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-chart-column"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Lesson Plan Reports & Syllabus Coverage</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Institutional curriculum execution analytics, topic coverage rates, and audit reports</p>
        </div>
        <div>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-print"></i>
                <span>Print / PDF Export</span>
            </button>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="no-print bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('lesson-plans.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Class</label>
                <select name="class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    <option value="">All classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Subject</label>
                <select name="subject_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    <option value="">All subjects</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ $subjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Faculty / Teacher</label>
                <select name="staff_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    <option value="">All teachers</option>
                    @foreach($staffMembers as $st)
                        <option value="{{ $st->id }}" {{ $staffId == $st->id ? 'selected' : '' }}>{{ $st->first_name }} {{ $st->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center justify-center space-x-1 shadow-xs">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Apply Filters</span>
                </button>
                @if($classId || $subjectId || $staffId)
                <a href="{{ route('lesson-plans.reports') }}" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-500" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Printable Report Container -->
    <div id="printableLessonReport" class="space-y-6">
        <!-- Syllabus Completion Overview Banner (Matching Live Demo) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $school->name ?? 'Greenwood High School' }}</span>
                    <h2 class="text-lg font-black text-slate-900 mt-0.5">Overall Syllabus Completion: <span class="text-emerald-600 font-mono">{{ $overallRate }}%</span></h2>
                    <div class="flex flex-wrap items-center gap-3 text-xs mt-2 font-medium">
                        <span class="text-emerald-600 font-bold">● {{ $coveredTopics }} covered</span>
                        <span class="text-amber-600 font-bold">● {{ $partialTopics }} partial</span>
                        <span class="text-rose-600 font-bold">● {{ $notCoveredTopics }} not covered</span>
                        <span class="text-slate-400 font-bold">● {{ $pendingTopics }} pending</span>
                        <span class="text-slate-700 font-bold ml-2">({{ $totalTopics }} planned topics total)</span>
                    </div>
                </div>

                <div class="w-48">
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $overallRate)) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class & Subject Audit Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-xs">Curriculum Execution Audit by Plan ({{ $plans->count() }} Plans)</h3>
                <span class="text-[10px] text-slate-400 font-mono">Academic Session 2026–2027</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Plan Title</th>
                            <th class="py-2.5 px-3">Class / Section</th>
                            <th class="py-2.5 px-3">Subject</th>
                            <th class="py-2.5 px-3">Teacher</th>
                            <th class="py-2.5 px-3">Week</th>
                            <th class="py-2.5 px-3">Topics</th>
                            <th class="py-2.5 px-3 w-48">Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($plans as $p)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-3 font-bold text-slate-900 text-xs">
                                {{ $p->title }}
                            </td>
                            <td class="py-2.5 px-3">
                                {{ $p->schoolClass->name ?? 'Class' }} @if($p->section) • Sec {{ $p->section->name }} @endif
                            </td>
                            <td class="py-2.5 px-3 font-bold text-emerald-700">
                                {{ $p->subject->name ?? 'Subject' }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-800">
                                {{ $p->staff->first_name ?? 'Faculty' }} {{ $p->staff->last_name ?? '' }}
                            </td>
                            <td class="py-2.5 px-3 font-mono text-[10px] text-slate-500">
                                Week {{ $p->week_number }}
                            </td>
                            <td class="py-2.5 px-3 font-mono">
                                {{ $p->topics->where('coverage_status', 'Covered')->count() }} / {{ $p->topics->count() }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="font-bold font-mono text-emerald-600">{{ $p->coverage_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, max(0, $p->coverage_percentage)) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">No approved plans matching filter criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


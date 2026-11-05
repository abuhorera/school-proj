@extends('layouts.app')

@section('title', 'Syllabus Coverage - Curriculum & Syllabus')

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
                    <i class="fa-solid fa-bars-progress"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Syllabus Coverage Tracker</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Reconcile taught curriculum against planned weekly periods, record completion dates, and audit remedial topics</p>
        </div>
        <!-- Filter Tabs -->
        <div class="flex items-center space-x-1 p-1 bg-slate-100 rounded-xl text-xs font-bold">
            <a href="{{ route('lesson-plans.coverage', ['show' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition {{ $show == 'all' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                All Plans
            </a>
            <a href="{{ route('lesson-plans.coverage', ['show' => 'pending']) }}" class="px-3 py-1.5 rounded-lg transition {{ $show == 'pending' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Needs Marking
            </a>
        </div>
    </div>

    <!-- Coverage Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Approved Curriculum Plans ({{ $plans->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Plan</th>
                        <th class="py-3 px-4">Class / Subject</th>
                        <th class="py-3 px-4">Week</th>
                        <th class="py-3 px-4 w-72">Coverage Progress</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($plans as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="hover:text-emerald-600 transition">
                                    {{ $p->title }}
                                </a>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                Teacher: {{ $p->staff->first_name ?? 'Faculty' }} {{ $p->staff->last_name ?? '' }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800">{{ $p->schoolClass->name ?? 'Class' }} @if($p->section) • Sec {{ $p->section->name }} @endif</div>
                            <div class="text-[10px] text-emerald-700 font-semibold">{{ $p->subject->name ?? 'Subject' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-700">
                            <div>Week {{ $p->week_number }}</div>
                            <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($p->week_start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($p->week_end_date)->format('d M Y') }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold font-mono {{ $p->coverage_percentage >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">{{ $p->coverage_percentage }}%</span>
                                    <span class="text-slate-400 text-[10px]">{{ $p->covered_count }}/{{ $p->topics_count }} covered</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $p->coverage_percentage)) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('lesson-plans.coverage.detail', $p->id) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition inline-flex items-center space-x-1.5 shadow-xs">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                <span>Mark Coverage</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400">No approved lesson plans to reconcile.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Lesson Plan — ' . $plan->title)

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

    <!-- Plan Header Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-book-bookmark"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">{{ $plan->title }}</h1>
                @php
                    $statusStyles = [
                        'Draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'Pending HOD' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'Pending Principal' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Needs revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                    ];
                    $style = $statusStyles[$plan->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                @endphp
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $style }}">
                    {{ $plan->status }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-3">
                <span><i class="fa-solid fa-chalkboard-user mr-1 text-slate-400"></i>{{ $plan->schoolClass->name ?? 'Class' }} @if($plan->section) • Sec {{ $plan->section->name }} @endif</span>
                <span>•</span>
                <span class="text-emerald-700 font-bold"><i class="fa-solid fa-book mr-1"></i>{{ $plan->subject->name ?? 'Subject' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-user-tie mr-1 text-slate-400"></i>{{ $plan->staff->first_name ?? 'Faculty' }} {{ $plan->staff->last_name ?? '' }}</span>
                <span>•</span>
                <span class="font-mono"><i class="fa-solid fa-calendar mr-1 text-slate-400"></i>Week {{ $plan->week_number }} ({{ \Carbon\Carbon::parse($plan->week_start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($plan->week_end_date)->format('d M Y') }})</span>
                <span>•</span>
                <span class="font-mono font-bold text-slate-700">{{ $plan->planned_periods }} Periods</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Clone button -->
            <form action="{{ route('lesson-plans.plans.clone', $plan->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-2 border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs" title="Duplicate to next week">
                    <i class="fa-solid fa-copy text-xs"></i>
                    <span>Clone to Next Week</span>
                </button>
            </form>

            @if(in_array($plan->status, ['Draft', 'Needs revision']))
            <form action="{{ route('lesson-plans.plans.submit', $plan->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Submit for Review</span>
                </button>
            </form>
            @endif

            @if($plan->status == 'Approved')
            <a href="{{ route('lesson-plans.coverage.detail', $plan->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-bars-progress text-xs"></i>
                <span>Mark Coverage</span>
            </a>
            @endif

            <a href="{{ route('lesson-plans.plans.edit', $plan->id) }}" class="p-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl" title="Edit Plan">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Learning Objectives -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 space-y-2">
            <h3 class="font-bold text-slate-800 text-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-bullseye text-emerald-600"></i>
                <span>Learning Objectives & Pedagogical Goals</span>
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                {{ $plan->learning_objectives ?? 'No learning objectives specified.' }}
            </p>
        </div>

        <!-- Methods & Aids Badges -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 space-y-3">
            <div>
                <h3 class="font-bold text-slate-800 text-xs mb-1.5 flex items-center space-x-1.5">
                    <i class="fa-solid fa-chalkboard text-emerald-600"></i>
                    <span>Teaching Methods</span>
                </h3>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($plan->teaching_methods ?? ['Lecture', 'Discussion'] as $method)
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            {{ $method }}
                        </span>
                    @empty
                        <span class="text-xs text-slate-400 italic">None selected</span>
                    @endforelse
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <h3 class="font-bold text-slate-800 text-xs mb-1.5 flex items-center space-x-1.5">
                    <i class="fa-solid fa-tv text-sky-600"></i>
                    <span>Teaching Aids & Media</span>
                </h3>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($plan->teaching_aids ?? ['Whiteboard'] as $aid)
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-100">
                            {{ $aid }}
                        </span>
                    @empty
                        <span class="text-xs text-slate-400 italic">None selected</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Homework & Resources -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 space-y-2">
            <h3 class="font-bold text-slate-800 text-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-pencil text-amber-600"></i>
                <span>Homework & Practice Assignments</span>
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                {{ $plan->homework_planned ?? 'No homework assigned for this plan.' }}
            </p>
        </div>

        <!-- Review & Approval Trail -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 space-y-2">
            <h3 class="font-bold text-slate-800 text-xs flex items-center space-x-1.5">
                <i class="fa-solid fa-stamp text-purple-600"></i>
                <span>Review & Approval Trail</span>
            </h3>
            <div class="space-y-1.5 text-xs text-slate-600">
                @if($plan->submitted_at)
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-400">Submitted:</span>
                        <span class="font-mono">{{ \Carbon\Carbon::parse($plan->submitted_at)->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
                @if($plan->review_feedback)
                    <div class="p-2.5 bg-slate-50 rounded-xl text-[11px] border border-slate-100 text-slate-700 italic">
                        Feedback: {{ $plan->review_feedback }}
                    </div>
                @endif
                @if($plan->approved_at)
                    <div class="flex items-center justify-between text-[11px] text-emerald-700 font-bold">
                        <span>Approved:</span>
                        <span class="font-mono">{{ \Carbon\Carbon::parse($plan->approved_at)->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
                @if(!$plan->submitted_at && !$plan->approved_at)
                    <p class="text-slate-400 italic text-[11px]">Plan has not been submitted for review yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Topics & Syllabus Coverage Ledger -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Topics Breakdown & Syllabus Coverage ({{ $plan->topics->count() }})</h3>
                <p class="text-xs text-slate-400 mt-0.5">Overall Coverage Progress: <span class="font-mono font-bold text-emerald-600">{{ $plan->coverage_percentage }}%</span></p>
            </div>
            @if($plan->status == 'Approved')
            <a href="{{ route('lesson-plans.coverage.detail', $plan->id) }}" class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-bold transition flex items-center space-x-1">
                <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                <span>Update Coverage</span>
            </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Topic Name</th>
                        <th class="py-3 px-4">Periods</th>
                        <th class="py-3 px-4">Coverage Status</th>
                        <th class="py-3 px-4">Covered On</th>
                        <th class="py-3 px-4">Notes / Remedial Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($plan->topics as $index => $t)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                            {{ $t->topic_name }}
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">
                            {{ $t->periods_allocated }} Period(s)
                        </td>
                        <td class="py-3 px-4">
                            @if($t->coverage_status == 'Covered')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[9px] mr-0.5"></i> Covered
                                </span>
                            @elseif($t->coverage_status == 'Partial')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Partial
                                </span>
                            @elseif($t->coverage_status == 'Not Covered')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    Not Covered
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">
                            {{ $t->covered_on ? \Carbon\Carbon::parse($t->covered_on)->format('d M Y') : '—' }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 text-[11px] max-w-sm">
                            {{ $t->coverage_reason ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No individual topics listed for this plan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


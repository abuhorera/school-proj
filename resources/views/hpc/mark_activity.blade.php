@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-5xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('hpc.activities') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Activities</span>
        </a>
        <span class="text-xs px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 font-semibold">
            {{ $activity->schoolClass->name ?? 'Class' }} · {{ $activity->domain }}
        </span>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 mb-6 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white mb-1">
            Mark — {{ $activity->title }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Assess developmental competencies for all learners in this activity using the 3-point attainment rubric.
        </p>
        @if($activity->prompt)
            <div class="mt-3 p-3 rounded-xl bg-violet-50/50 dark:bg-violet-950/20 border border-violet-100 dark:border-violet-900/40 text-xs text-slate-700 dark:text-slate-300">
                <span class="font-semibold text-violet-700 dark:text-violet-300">Task Scenario:</span> {{ $activity->prompt }}
            </div>
        @endif
    </div>

    <!-- Marking Form -->
    <form method="POST" action="{{ route('hpc.activities.save-mark', $activity->id) }}">
        @csrf

        <div class="space-y-6">
            @foreach($students as $stu)
                @php
                    $mark = $existingMarks->get($stu->id);
                    $levels = $mark ? ($mark->levels_json ?? []) : [];
                @endphp
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-5 shadow-sm">
                    <!-- Student Header -->
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0">
                            {{ substr($stu->first_name, 0, 1) }}{{ substr($stu->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ $stu->full_name ?? ($stu->first_name . ' ' . $stu->last_name) }}
                            </h3>
                            <div class="text-[11px] text-slate-400">
                                <span>{{ $stu->admission_no ?? 'ADM-' . $stu->id }}</span>
                                <span class="mx-1.5">&middot;</span>
                                <span>Roll {{ $stu->roll_no ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Criteria Levels Grid -->
                    <div class="py-4 border-b border-slate-100 dark:border-slate-700">
                        <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-3 uppercase tracking-wider">
                            Attainment Rubric
                        </div>

                        <div class="space-y-3">
                            @foreach($rubricCriteria as $crit)
                                @php
                                    $currVal = $levels[$crit] ?? '';
                                @endphp
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $crit }}</span>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs cursor-pointer hover:bg-white dark:hover:bg-slate-800 transition">
                                            <input type="radio" name="levels[{{ $stu->id }}][{{ $crit }}]" value="B" {{ $currVal === 'B' ? 'checked' : '' }} class="text-violet-600 focus:ring-violet-500">
                                            <span class="text-amber-700 dark:text-amber-300 font-semibold">Beginner (B)</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs cursor-pointer hover:bg-white dark:hover:bg-slate-800 transition">
                                            <input type="radio" name="levels[{{ $stu->id }}][{{ $crit }}]" value="P" {{ $currVal === 'P' ? 'checked' : '' }} class="text-violet-600 focus:ring-violet-500">
                                            <span class="text-blue-700 dark:text-blue-300 font-semibold">Proficient (P)</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs cursor-pointer hover:bg-white dark:hover:bg-slate-800 transition">
                                            <input type="radio" name="levels[{{ $stu->id }}][{{ $crit }}]" value="A" {{ $currVal === 'A' ? 'checked' : '' }} class="text-violet-600 focus:ring-violet-500">
                                            <span class="text-emerald-700 dark:text-emerald-300 font-semibold">Advanced (A)</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs cursor-pointer hover:bg-white dark:hover:bg-slate-800 transition text-slate-400">
                                            <input type="radio" name="levels[{{ $stu->id }}][{{ $crit }}]" value="" {{ empty($currVal) ? 'checked' : '' }} class="text-slate-400 focus:ring-slate-400">
                                            <span>Not yet observed</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Qualitative Notes -->
                    <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                How the learner did
                            </label>
                            <textarea name="observations[{{ $stu->id }}]" rows="2" placeholder="Describe learner engagement, peer collaboration, and strengths demonstrated..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-violet-500">{{ $mark->observations ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Challenges faced, and how they were overcome
                            </label>
                            <textarea name="challenges[{{ $stu->id }}]" rows="2" placeholder="E.g. Needed slight encouragement initially, but participated confidently..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-violet-500">{{ $mark->challenges ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Submit Footer -->
        <div class="sticky bottom-4 mt-6 p-4 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl flex items-center justify-between">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Saving marks for <strong class="text-slate-800 dark:text-slate-200">{{ $students->count() }}</strong> learners.
            </span>
            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-md">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Save All {{ $students->count() }} Learners
            </button>
        </div>
    </form>
</div>
@endsection


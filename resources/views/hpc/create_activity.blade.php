@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('hpc.activities') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Activities</span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus text-violet-600"></i>
                <span>New Classroom Activity</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Design a learning experience mapped to NEP 2020 competency rubrics.
            </p>
        </div>

        <form method="POST" action="{{ route('hpc.activities.store') }}" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Card Edition / Framework</label>
                    <select name="framework_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="">Select Edition…</option>
                        @foreach($frameworks as $fw)
                            <option value="{{ $fw->id }}">{{ $fw->title }} ({{ $fw->stage }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Activity Type <span class="text-red-500">*</span></label>
                    <select name="activity_type" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="Learning Activity">Learning Activity</option>
                        <option value="Group Project Work">Group Project Work</option>
                        <option value="Problem-based Task">Problem-based Task</option>
                        <option value="Creative Expression">Creative Expression</option>
                        <option value="Physical Game">Physical Game</option>
                        <option value="Experiential Inquiry">Experiential Inquiry</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Developmental Domain <span class="text-red-500">*</span></label>
                    <select name="domain" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="">Select Domain…</option>
                        @foreach($domains as $dom)
                            <option value="{{ $dom }}">{{ $dom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Class <span class="text-red-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="">Select Class…</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Term</label>
                    <input type="text" name="term" value="Full year" placeholder="e.g. Full year, Term 1" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Interaction Type</label>
                    <select name="interaction_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="Individual">Individual</option>
                        <option value="Pair">Pair</option>
                        <option value="Small Group">Small Group</option>
                        <option value="Whole Class">Whole Class</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Duration (Minutes)</label>
                    <input type="number" name="duration_minutes" value="45" min="10" max="180" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Activity Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Rhythm & Nature Sound Symphony, Leaf Pattern Inquiry" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Prompt & Instructions for Learners</label>
                <textarea name="prompt" rows="3" placeholder="Describe the task instructions, guiding questions, or scenario..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pedagogical Approaches (Tick all that apply)</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($pedagogies as $ped)
                        <label class="inline-flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40 cursor-pointer transition text-xs">
                            <input type="checkbox" name="pedagogies[]" value="{{ $ped }}" class="rounded text-violet-600 focus:ring-violet-500">
                            <span class="text-slate-700 dark:text-slate-300">{{ $ped }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                <a href="{{ route('hpc.activities') }}" class="px-4 py-2.5 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                    Create Activity
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


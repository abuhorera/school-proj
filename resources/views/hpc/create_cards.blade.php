@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('hpc.cards') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Progress Cards</span>
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-6">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-id-card-clip text-violet-600"></i>
                <span>Create Progress Cards</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Pick a class, then tick the learners who need a card.
            </p>
        </div>

        <!-- Step 1: Select Class & Term -->
        <div class="p-5 border-b border-slate-100 dark:border-slate-700">
            <form method="GET" action="{{ route('hpc.cards.create') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Step 1 — Which class? <span class="text-red-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                        <option value="">Choose a class…</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Term</label>
                    <input type="text" name="term" value="{{ $term }}" placeholder="e.g. Full year, Term 1" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2.5 px-3 focus:ring-violet-500">
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-2.5 text-xs font-semibold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 rounded-xl transition">
                        Show Learners
                    </button>
                </div>
            </form>
            <p class="text-[11px] text-slate-400 mt-2">
                The term matters only if your school issues more than one card a year. Leave it blank and each learner gets one card for the whole year.
            </p>
        </div>

        <!-- Step 2: Learners Checklist -->
        @if($selectedClassId && $students->isNotEmpty())
            <form method="POST" action="{{ route('hpc.cards.store') }}" class="p-5">
                @csrf
                <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="term" value="{{ $term }}">

                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-700">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                        Step 2 — Select Learners ({{ $students->count() }} enrolled)
                    </div>
                    <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-violet-600 cursor-pointer">
                        <input type="checkbox" id="selectAll" checked onchange="toggleSelectAll(this)" class="rounded text-violet-600 focus:ring-violet-500">
                        <span>Select All</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6 max-h-96 overflow-y-auto pr-1">
                    @foreach($students as $stu)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40 cursor-pointer transition">
                            <input type="checkbox" name="student_ids[]" value="{{ $stu->id }}" checked class="stu-checkbox rounded text-violet-600 focus:ring-violet-500">
                            <div class="w-8 h-8 rounded-full bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ substr($stu->first_name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                    {{ $stu->full_name ?? ($stu->first_name . ' ' . $stu->last_name) }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $stu->admission_no ?? 'ADM-' . $stu->id }} &middot; Roll {{ $stu->roll_no ?? '-' }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-xs text-slate-500">One card per learner will be initialized in Draft state.</span>
                    <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                        <i class="fa-solid fa-plus-circle mr-1"></i> Generate Cards for Selected
                    </button>
                </div>
            </form>
        @else
            <div class="p-8 text-center text-slate-400">
                <i class="fa-solid fa-arrow-up text-xl mb-2 text-slate-300 block"></i>
                <p class="text-xs font-medium text-slate-600 dark:text-slate-400">Choose a class above</p>
                <p class="text-[11px] text-slate-400">Its learners will appear here, ready to tick.</p>
            </div>
        @endif
    </div>
</div>

<script>
function toggleSelectAll(master) {
    document.querySelectorAll('.stu-checkbox').forEach(cb => {
        cb.checked = master.checked;
    });
}
</script>
@endsection


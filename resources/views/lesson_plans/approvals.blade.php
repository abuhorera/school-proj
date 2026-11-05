@extends('layouts.app')

@section('title', 'Lesson Plan Approvals (Principal) - Curriculum & Syllabus')

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
    @if(session('error'))
    <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-500 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-stamp"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Plans Awaiting Final Approval</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Principal executive sign-off for HOD-endorsed weekly teaching plans</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 font-mono">
            {{ $plans->count() }} Ready for Principal Sign-off
        </span>
    </div>

    <!-- Class Filter Pills -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-3.5 flex items-center space-x-1.5 overflow-x-auto scrollbar-none text-xs">
        <a href="{{ route('lesson-plans.approvals') }}" class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ empty($classId) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            All classes
        </a>
        @foreach($classes as $c)
        <a href="{{ route('lesson-plans.approvals', ['class_id' => $c->id]) }}" class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $classId == $c->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $c->name }}
        </a>
        @endforeach
    </div>

    <!-- Approvals Table with Bulk Selection (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <form id="bulkForm" action="{{ route('lesson-plans.approvals.bulk') }}" method="POST">
            @csrf

            <div class="p-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <label for="selectAll" class="text-xs font-bold text-slate-700 cursor-pointer">Select All ({{ $plans->count() }})</label>
                </div>
                <button type="submit" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-check-double text-[11px]"></i>
                    <span>Approve Selected</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 w-10"></th>
                            <th class="py-3 px-4">Plan</th>
                            <th class="py-3 px-4">Teacher</th>
                            <th class="py-3 px-4">Class / Subject</th>
                            <th class="py-3 px-4">Week</th>
                            <th class="py-3 px-4">Topics</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($plans as $p)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4">
                                <input type="checkbox" name="plan_ids[]" value="{{ $p->id }}" class="plan-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 text-xs">
                                    <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="hover:text-emerald-600 transition">
                                        {{ $p->title }}
                                    </a>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    Endorsed by HOD • {{ $p->planned_periods }} Periods
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800">{{ $p->staff->first_name ?? 'Faculty' }} {{ $p->staff->last_name ?? '' }}</span>
                                <div class="text-[10px] text-slate-400">{{ $p->staff->department ?? 'Academic' }}</div>
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
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $p->topics->count() }} Topics
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="px-2.5 py-1 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-lg text-[10px] font-bold transition">
                                        Open
                                    </a>
                                    <form action="{{ route('lesson-plans.approvals.approve', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold transition shadow-xs flex items-center space-x-1">
                                            <i class="fa-solid fa-stamp text-[9px]"></i>
                                            <span>Approve</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">No lesson plans awaiting final Principal approval.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSelectAll(master) {
    const boxes = document.querySelectorAll('.plan-checkbox');
    boxes.forEach(b => b.checked = master.checked);
}
</script>
@endsection


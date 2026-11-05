@extends('layouts.app')

@section('title', 'Lesson Plan Review (HOD) - Curriculum & Syllabus')

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
                    <i class="fa-solid fa-user-check"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Plans Awaiting HOD Review</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Review department faculty submissions, verify academic standards, and endorse for Principal sign-off</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
            {{ $plans->count() }} Pending HOD Review
        </span>
    </div>

    <!-- Review Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Submitted Lesson Plans Queue ({{ $plans->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Plan</th>
                        <th class="py-3 px-4">Teacher</th>
                        <th class="py-3 px-4">Class / Subject</th>
                        <th class="py-3 px-4">Week</th>
                        <th class="py-3 px-4">Topics</th>
                        <th class="py-3 px-4">Submitted</th>
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
                                {{ $p->planned_periods }} Periods
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
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                            {{ $p->submitted_at ? \Carbon\Carbon::parse($p->submitted_at)->diffForHumans() : 'Recently' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <form action="{{ route('lesson-plans.review.action', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="forward_to_principal">
                                    <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold transition shadow-xs flex items-center space-x-1" title="Endorse and forward to Principal">
                                        <i class="fa-solid fa-check text-[9px]"></i>
                                        <span>Endorse</span>
                                    </button>
                                </form>

                                <button onclick="openRevisionModal({{ $p->id }}, '{{ addslashes($p->title) }}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="Request Changes">
                                    <i class="fa-solid fa-rotate-left text-[9px]"></i>
                                    <span>Revise</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">Nothing awaiting your review. All plans are up-to-date!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Request Revision -->
<div id="revisionModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Request Plan Revision</h3>
            <button onclick="document.getElementById('revisionModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="revisionForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="action" value="needs_revision">
            <div>
                <p class="text-xs text-slate-500 mb-2">Provide specific feedback for the teacher to address:</p>
                <textarea name="feedback" required rows="3.5" placeholder="Specify needed changes in period allocation, learning outcomes, or practical tasks..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-xs">Return for Revision</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRevisionModal(planId, title) {
    document.getElementById('revisionForm').action = "/school/lesson-plans/review/" + planId;
    document.getElementById('revisionModal').classList.remove('hidden');
}
</script>
@endsection


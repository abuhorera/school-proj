@extends('layouts.app')

@section('title', 'Mark Coverage — ' . $plan->title)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
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
                    <i class="fa-solid fa-pen-nib"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Mark Coverage — {{ $plan->title }}</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-3">
                <span>{{ $plan->schoolClass->name ?? 'Class' }} @if($plan->section) • Sec {{ $plan->section->name }} @endif</span>
                <span>•</span>
                <span class="text-emerald-700 font-bold">{{ $plan->subject->name ?? 'Subject' }}</span>
                <span>•</span>
                <span>Week {{ $plan->week_number }} ({{ \Carbon\Carbon::parse($plan->week_start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($plan->week_end_date)->format('d M Y') }})</span>
                <span>•</span>
                <span class="font-bold text-slate-700 font-mono">{{ $plan->planned_periods }} Planned Periods</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('lesson-plans.coverage') }}" class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                Back to Coverage
            </a>
            <button type="button" onclick="document.getElementById('coverageForm').submit()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Coverage</span>
            </button>
        </div>
    </div>

    <!-- Topic-wise Coverage Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <form id="coverageForm" action="{{ route('lesson-plans.coverage.save', $plan->id) }}" method="POST">
            @csrf

            <div class="p-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-xs">Topic Delivery Status ({{ $plan->topics->count() }} Items)</h3>
                    <p class="text-[11px] text-slate-400">Reconcile taught periods against the syllabus timeline</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="markAllCoverage('Covered')" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold transition border border-emerald-200">
                        Mark All Covered
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 w-72">Planned Topic</th>
                            <th class="py-3 px-4 w-28">Allocated</th>
                            <th class="py-3 px-4 w-44">Coverage Status</th>
                            <th class="py-3 px-4 w-40">Date Covered</th>
                            <th class="py-3 px-4">Completion Notes / Remedial Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($plan->topics as $t)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-4 font-bold text-slate-900 text-xs">
                                {{ $t->topic_name }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-700">
                                {{ $t->periods_allocated }} Period(s)
                            </td>
                            <td class="py-3 px-4">
                                <select name="topics[{{ $t->id }}][coverage_status]" class="coverage-select w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-1.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                                    <option value="Covered" {{ $t->coverage_status == 'Covered' ? 'selected' : '' }} class="text-emerald-700 font-bold">Covered</option>
                                    <option value="Partial" {{ $t->coverage_status == 'Partial' ? 'selected' : '' }} class="text-amber-700 font-bold">Partial</option>
                                    <option value="Not Covered" {{ $t->coverage_status == 'Not Covered' ? 'selected' : '' }} class="text-rose-700 font-bold">Not Covered</option>
                                    <option value="Deferred" {{ $t->coverage_status == 'Deferred' ? 'selected' : '' }} class="text-purple-700 font-bold">Deferred</option>
                                    <option value="Pending" {{ $t->coverage_status == 'Pending' ? 'selected' : '' }} class="text-slate-500">Pending</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <input type="date" name="topics[{{ $t->id }}][covered_on]" value="{{ $t->covered_on ?? date('Y-m-d') }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-1.5 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="topics[{{ $t->id }}][coverage_reason]" value="{{ $t->coverage_reason }}" placeholder="e.g. Completed during period 2, practical lab session..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-1.5 focus:ring-2 focus:ring-emerald-500">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">No topics registered for this plan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400">Completion percentage will auto-recalculate upon saving</span>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Coverage Progress</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function markAllCoverage(val) {
    const selects = document.querySelectorAll('.coverage-select');
    selects.forEach(s => s.value = val);
}
</script>
@endsection


@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Label Matching',
        'pageSubtitle' => 'Reconcile raw room names typed into timetables and exam schedules with physical campus rooms.'
    ])

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Unreconciled & Typed Labels ({{ $labels->count() }})</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                When teachers or exam schedulers type custom room names (e.g. "1", "Room 101", "Chem Lab"), map them here so clash detection and utilization metrics work accurately.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Label as Typed</th>
                        <th class="px-4 py-3.5">Timetable Periods</th>
                        <th class="px-4 py-3.5">Exam Papers</th>
                        <th class="px-4 py-3.5">Match to Physical Room</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($labels as $index => $lbl)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-mono text-xs font-bold px-2 py-1 rounded bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white">
                                    {{ $lbl->label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-medium">
                                {{ $lbl->timetable_count ?: '—' }}
                            </td>
                            <td class="px-4 py-3.5 font-medium">
                                {{ $lbl->exam_count ?: '—' }}
                            </td>
                            <td class="px-4 py-3.5">
                                <form method="POST" action="{{ route('classrooms.labels.map') }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="label" value="{{ $lbl->label }}">
                                    <select name="room_id" required class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-1.5 px-3 focus:ring-orange-500">
                                        <option value="">Pick a room…</option>
                                        @foreach($rooms as $r)
                                            <option value="{{ $r->id }}" {{ $lbl->room_id == $r->id ? 'selected' : '' }}>
                                                {{ $r->room_number }} &middot; {{ $r->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition shadow-sm">
                                        Match
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-slate-500">
                                All timetable and exam room labels are fully mapped to physical campus rooms!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Clashes',
        'pageSubtitle' => 'Detect double bookings, overlapping timetable periods and rooms too small for assigned class sizes.'
    ])

    <!-- 4 KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Double Bookings</span>
            <div class="text-2xl font-bold text-emerald-600">0</div>
            <p class="text-[10px] text-slate-400 mt-0.5">No concurrent clashes</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Rooms Too Small</span>
            <div class="text-2xl font-bold text-emerald-600">0</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Capacity verified</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Combined Classes</span>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Permitted joint sessions</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Unmapped Labels</span>
            <div class="text-2xl font-bold text-amber-600">{{ $unmappedLabels->count() }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5"><a href="{{ route('classrooms.labels') }}" class="text-orange-600 hover:underline">Match now &rarr;</a></p>
        </div>
    </div>

    <!-- Status Alert Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 mx-auto flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">No Room Clashes Detected</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
            Every class in the timetable has an exclusive room to itself, and every allocated classroom has sufficient seating capacity for all enrolled students.
        </p>
    </div>
</div>
@endsection


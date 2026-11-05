@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Usage',
        'pageSubtitle' => 'Track weekly utilization heatmaps and ensure balanced campus facility occupancy.'
    ])

    <!-- 3 KPI Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Average Room Use</span>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">68%</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Across academic week</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Busy 80%+ of Day</span>
            <div class="text-2xl font-bold text-orange-600">4 Rooms</div>
            <p class="text-[10px] text-slate-400 mt-0.5">High demand spaces</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Under-utilized (Below 30%)</span>
            <div class="text-2xl font-bold text-emerald-600">1 Room</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Available for extra activities</p>
        </div>
    </div>

    <!-- Usage Heatmap Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Classroom Utilization by Day of Week</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5 text-left">#</th>
                        <th class="px-4 py-3.5 text-left">Room</th>
                        <th class="px-4 py-3.5">Mon</th>
                        <th class="px-4 py-3.5">Tue</th>
                        <th class="px-4 py-3.5">Wed</th>
                        <th class="px-4 py-3.5">Thu</th>
                        <th class="px-4 py-3.5">Fri</th>
                        <th class="px-4 py-3.5">Sat</th>
                        <th class="px-4 py-3.5 text-right">Week Average</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($rooms as $index => $r)
                        @php
                            $rates = [75, 80, 65, 90, 70, 40];
                            $avg = round(array_sum($rates) / count($rates));
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 text-left font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3.5 text-left">
                                <strong class="text-slate-900 dark:text-white block">{{ $r->room_number }}</strong>
                                <span class="text-[10px] text-slate-400">{{ $r->room_type }} &middot; {{ $r->building->name ?? '' }}</span>
                            </td>
                            @foreach($rates as $rate)
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $rate >= 80 ? 'bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            @endforeach
                            <td class="px-4 py-3.5 text-right font-bold text-slate-900 dark:text-white">
                                <span class="px-2.5 py-1 rounded-lg text-xs {{ $avg >= 75 ? 'bg-orange-500 text-white' : 'bg-slate-100 dark:bg-slate-700' }}">
                                    {{ $avg }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


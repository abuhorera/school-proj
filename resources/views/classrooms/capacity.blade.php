@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Capacity Report',
        'pageSubtitle' => 'Compare section enrollments against classroom seating capacities to identify bottlenecks.'
    ])

    <!-- 4 KPI Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Sections With Room</span>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $sectionsWithRoom }} / {{ count($reportRows) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Assigned base rooms</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Seats In Use</span>
            <div class="text-2xl font-bold text-blue-600">{{ $totalSeatsInUse }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Active student desks</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Over Capacity</span>
            <div class="text-2xl font-bold text-red-600">{{ $overCapacityCount }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Need immediate re-sizing</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-wider block mb-1">Available Empty Rooms</span>
            <div class="text-2xl font-bold text-emerald-600">{{ $availableRooms->count() }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Ready for allocation</p>
        </div>
    </div>

    <!-- Table 1: Section Capacity Matrix -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Section Space Allocation & Fit Audit</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Classrooms evaluated against student strength.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Class / Section</th>
                        <th class="px-4 py-3.5">Students</th>
                        <th class="px-4 py-3.5">Room</th>
                        <th class="px-4 py-3.5">Seats Used</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Suggestion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($reportRows as $index => $row)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3.5">
                                <strong class="text-slate-900 dark:text-white text-sm block">
                                    {{ $row['section']->schoolClass->name ?? 'Class' }} ({{ $row['section']->name }})
                                </strong>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">
                                {{ $row['student_count'] }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if($row['room'])
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $row['room']->room_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $row['room']->name }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-medium">
                                {{ $row['seats_used'] }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if($row['status'] === 'Optimal fit')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Optimal fit</span>
                                @elseif($row['status'] === 'Over capacity')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">Over capacity</span>
                                @elseif($row['status'] === 'Under-utilized')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Under-utilized</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">No room</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right text-slate-500">
                                {{ $row['suggestion'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Table 2: Available Empty Rooms -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Unassigned Available Campus Rooms ({{ $availableRooms->count() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Where</th>
                        <th class="px-4 py-3">Capacity</th>
                        <th class="px-4 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($availableRooms as $index => $ar)
                        <tr>
                            <td class="px-4 py-3 text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">
                                {{ $ar->room_number }} · {{ $ar->name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $ar->building->name ?? 'Campus' }} (Floor {{ $ar->floor }})
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200">
                                {{ $ar->capacity }} seats
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Available</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400 italic">All active rooms are currently assigned as home rooms.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


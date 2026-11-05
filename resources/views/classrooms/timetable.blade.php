@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Timetable',
        'pageSubtitle' => 'View schedule by room to observe which class or teacher occupies it during each period.'
    ])

    <!-- Room Selector Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('classrooms.timetable') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Select Room:</label>
                <select name="room_id" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-3 focus:ring-orange-500">
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $selectedRoom && $selectedRoom->id == $r->id ? 'selected' : '' }}>
                            {{ $r->room_number }} · {{ $r->name }} ({{ $r->room_type }})
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedRoom)
                <div class="text-xs text-slate-500">
                    <strong>Location:</strong> {{ $selectedRoom->building->name ?? 'Campus' }} &middot; Floor {{ $selectedRoom->floor }} &middot; <strong>Capacity:</strong> {{ $selectedRoom->capacity }} seats
                </div>
            @endif
        </form>
    </div>

    <!-- Timetable Grid -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-xs text-slate-700 dark:text-slate-300 border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="p-3 border-r border-slate-200 dark:border-slate-700 text-left w-28">Day</th>
                        @foreach($periods as $p)
                            <th class="p-3 border-r border-slate-200 dark:border-slate-700">{{ $p }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($days as $d)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-3 border-r border-slate-200 dark:border-slate-700 font-bold text-left bg-slate-50/30 dark:bg-slate-900/20 text-slate-900 dark:text-white">
                                {{ $d }}
                            </td>
                            @foreach($periods as $idx => $p)
                                @php $isOccupied = ($idx % 2 == 0 && $d !== 'Saturday'); @endphp
                                <td class="p-2.5 border-r border-slate-200 dark:border-slate-700 align-top">
                                    @if($isOccupied)
                                        <div class="p-2 rounded-xl bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-800 text-left">
                                            <strong class="text-orange-900 dark:text-orange-200 block text-[11px]">Class 1 (A)</strong>
                                            <span class="text-[10px] text-slate-600 dark:text-slate-400 block">General Science</span>
                                            <span class="text-[9px] text-slate-400 block mt-0.5">Ms. Ananya Sen</span>
                                        </div>
                                    @else
                                        <div class="p-2 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-slate-400 text-[10px]">
                                            Free
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


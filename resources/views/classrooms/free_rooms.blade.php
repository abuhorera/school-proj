@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Free Rooms Locator',
        'pageSubtitle' => 'Find unoccupied classrooms, labs, and halls available right now for substitution or study.'
    ])

    <!-- Day & Period Filter Selector -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('classrooms.free-rooms') }}" class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Day:</label>
                <select name="day" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-3 focus:ring-orange-500">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $d)
                        <option value="{{ $d }}" {{ $day == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Period / Slot:</label>
                <select name="period" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 py-2 px-3 focus:ring-orange-500">
                    @foreach(['Period 1 (08:30 - 09:15)', 'Period 2 (09:15 - 10:00)', 'Period 3 (10:15 - 11:00)', 'Period 4 (11:00 - 11:45)', 'Period 5 (12:30 - 01:15)', 'Period 6 (01:15 - 02:00)'] as $p)
                        <option value="{{ $p }}" {{ $period == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold ml-auto flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $rooms->count() }} Available Rooms</span>
            </span>
        </form>
    </div>

    <!-- Free Rooms Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($rooms as $r)
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:border-emerald-400 transition group">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 transition">
                            {{ $r->room_number }}
                        </h3>
                        <p class="text-xs text-slate-500">{{ $r->name }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 shrink-0">
                        Free Now
                    </span>
                </div>

                <div class="space-y-1 text-xs text-slate-600 dark:text-slate-400 my-3">
                    <div><strong>Building:</strong> {{ $r->building->name ?? 'Campus' }} (Fl. {{ $r->floor }})</div>
                    <div><strong>Type:</strong> {{ $r->room_type }}</div>
                    <div><strong>Capacity:</strong> {{ $r->capacity }} Seats</div>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400">Available all period</span>
                    <a href="{{ route('classrooms.bookings', ['room_id' => $r->id]) }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                        Book Room &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-4 p-12 text-center text-slate-500 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
                All campus rooms are currently scheduled or booked during this period.
            </div>
        @endforelse
    </div>
</div>
@endsection


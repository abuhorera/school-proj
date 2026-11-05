@extends('layouts.app')

@section('title', 'Manage Periods & Time Slots - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-800">Master School Periods & Time Slots</h3>
            <p class="text-xs text-slate-500">Configure bell schedule, instructional teaching periods, assembly, and lunch breaks</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addSlotModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add Period</span>
            </button>
            <a href="{{ route('academics.timetable') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-calendar-week text-indigo-600"></i>
                <span>View Class Timetables</span>
            </a>
        </div>
    </div>

    <!-- Time Slots List Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-regular fa-clock text-purple-600"></i>
                <span class="text-xs font-bold text-slate-800">Standard Daily Period Set ({{ $timeSlots->count() }} slots)</span>
            </div>
            <span class="text-xs text-slate-400">Ordered by daily chronological sequence</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">Order</th>
                        <th class="p-3.5">Period Name</th>
                        <th class="p-3.5">Time Range</th>
                        <th class="p-3.5 text-center">Duration</th>
                        <th class="p-3.5 text-center">Period Nature</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($timeSlots as $slot)
                        @php
                            $start = strtotime($slot->start_time);
                            $end = strtotime($slot->end_time);
                            $duration = round(($end - $start) / 60);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition {{ $slot->is_break ? 'bg-amber-50/30' : '' }}">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                #{{ $slot->sort_order }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-7 h-7 rounded-lg {{ $slot->is_break ? 'bg-amber-100 text-amber-700' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center font-bold text-[10px]">
                                        <i class="fa-regular {{ $slot->is_break ? 'fa-mug-hot' : 'fa-clock' }}"></i>
                                    </span>
                                    <span class="text-sm">{{ $slot->name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 text-slate-700 font-medium">
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ date('h:i A', $start) }}
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ date('h:i A', $end) }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-3.5 text-center font-semibold text-slate-700">
                                {{ $duration > 0 ? $duration . ' mins' : 'N/A' }}
                            </td>
                            <td class="p-3.5 text-center">
                                @if($slot->is_break)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        ☕ Break / Recess / Lunch
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        📖 Instructional Period
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <form method="POST" action="{{ route('academics.time-slots.delete', $slot->id) }}" onsubmit="return confirm('Delete period {{ $slot->name }}?');" class="inline">
                                    @csrf
                                    <button type="submit" title="Delete Period" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">
                                No period time slots created yet. Click "Add Period" to start.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Period / Time Slot -->
<div id="addSlotModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-regular fa-clock text-purple-400"></i>
                <span>Add School Period</span>
            </h4>
            <button onclick="document.getElementById('addSlotModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.time-slots.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Period Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Period 8, Zero Period, Morning Assembly" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Start Time <span class="text-rose-500">*</span></label>
                    <input type="time" name="start_time" required value="08:00" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">End Time <span class="text-rose-500">*</span></label>
                    <input type="time" name="end_time" required value="08:45" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Sequence / Sort Order</label>
                <input type="number" name="sort_order" placeholder="e.g. 1, 2, 3..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <input type="checkbox" id="is_break" name="is_break" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_break" class="text-xs text-slate-700 font-medium">This is a Break / Recess / Lunch (Non-teaching)</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addSlotModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Save Period
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


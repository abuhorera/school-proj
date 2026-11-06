@extends('layouts.app')

@section('title', 'Events & Holidays')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Communicate</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Events &amp; Holidays</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Events &amp; Holidays Calendar</h1>
                    <p class="text-xs text-slate-500">Academic calendars, sports events, public holidays, and staff meetings.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.events.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Event/Holiday</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('school.events') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Search Event</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by event title or details..." class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Event Type</label>
                <select name="type" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="all">All Event Types</option>
                    <option value="Event" {{ request('type') == 'Event' ? 'selected' : '' }}>Event</option>
                    <option value="Holiday" {{ request('type') == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                    <option value="Exam" {{ request('type') == 'Exam' ? 'selected' : '' }}>Exam Period</option>
                    <option value="Sports" {{ request('type') == 'Sports' ? 'selected' : '' }}>Sports &amp; Athletics</option>
                    <option value="Celebration" {{ request('type') == 'Celebration' ? 'selected' : '' }}>Celebration / Fest</option>
                    <option value="Meeting" {{ request('type') == 'Meeting' ? 'selected' : '' }}>Faculty Meeting</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('school.events') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Events Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Audience</th>
                        <th class="py-3 px-4">Start Date</th>
                        <th class="py-3 px-4">End Date</th>
                        <th class="py-3 px-4 text-center">Social</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($events as $index => $event)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-center text-slate-500 font-mono">
                            {{ $events->firstItem() + $index }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-sm hover:text-blue-600 cursor-pointer" onclick="viewEventModal({{ json_encode($event) }})">
                                {{ $event->title }}
                            </div>
                            @if($event->description)
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                    {{ Str::limit($event->description, 75) }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $type = strtolower($event->type ?? 'event');
                            @endphp
                            @if(str_contains($type, 'holiday'))
                                <span class="bg-rose-50 text-rose-700 font-bold px-2 py-0.5 rounded text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-umbrella-beach mr-1 text-rose-500"></i> Holiday
                                </span>
                            @elseif(str_contains($type, 'sports'))
                                <span class="bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-medal mr-1 text-amber-500"></i> Sports
                                </span>
                            @elseif(str_contains($type, 'exam'))
                                <span class="bg-purple-50 text-purple-700 font-bold px-2 py-0.5 rounded text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-file-signature mr-1 text-purple-500"></i> Exam
                                </span>
                            @else
                                <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded text-[11px] inline-flex items-center">
                                    <i class="fa-solid fa-calendar-day mr-1 text-blue-500"></i> {{ ucfirst($event->type ?? 'Event') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-slate-700 bg-slate-100 px-2 py-0.5 rounded font-semibold text-[11px]">
                                {{ $event->applies_to ?? 'All Staff & Students' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">
                            {{ $event->start_date ? date('d M, Y', strtotime($event->start_date)) : date('d M, Y', strtotime($event->event_date)) }}
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">
                            {{ $event->end_date ? date('d M, Y', strtotime($event->end_date)) : ($event->start_date ? date('d M, Y', strtotime($event->start_date)) : '-') }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center text-emerald-600 font-semibold text-[11px] bg-emerald-50 px-2 py-0.5 rounded-full">
                                <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Live
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="inline-flex items-center space-x-1">
                                <button type="button" onclick="viewEventModal({{ json_encode($event) }})" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View Details">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <a href="{{ route('school.events.edit', $event->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Event">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('school.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event / holiday?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Event">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
                            <p class="font-semibold text-slate-600 text-sm">No events or holidays registered.</p>
                            <p class="text-xs text-slate-400 mt-1">Click "Add New Event/Holiday" above to add sports meets, holidays, and campus celebrations.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: View Event Details -->
<div id="viewEventModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900" id="veTitle">Event Details</h3>
                    <span class="text-[10px] text-slate-500" id="veDates"></span>
                </div>
            </div>
            <button onclick="closeModal('viewEventModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="flex items-center space-x-2">
                <span class="text-slate-500 font-medium">Type:</span>
                <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded" id="veType"></span>
                <span class="text-slate-500 font-medium ml-2">Applies To:</span>
                <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded" id="veApplies"></span>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 whitespace-pre-wrap leading-relaxed" id="veDescription">
            </div>
        </div>

        <div class="flex justify-end pt-2 border-t border-slate-100">
            <button onclick="closeModal('viewEventModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function viewEventModal(event) {
    document.getElementById('veTitle').textContent = event.title;
    document.getElementById('veDates').textContent = 'Date: ' + (event.start_date || event.event_date) + ' to ' + (event.end_date || event.start_date || event.event_date);
    document.getElementById('veType').textContent = (event.type || 'Event').toUpperCase();
    document.getElementById('veApplies').textContent = event.applies_to || 'All Staff & Students';
    document.getElementById('veDescription').textContent = event.description || 'No additional description provided.';
    openModal('viewEventModal');
}
</script>
@endsection


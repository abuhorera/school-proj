@extends('layouts.app')

@section('title', 'Schedule Meetings - Parent Teacher Meetings')

@section('content')
<div class="space-y-6">
    @include('ptm.partials.subnav')

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
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">PTM Schedule Meetings</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure meeting agendas, dates, modes (in-person/hybrid/online), and guardian rosters</p>
        </div>
        <div>
            <a href="{{ route('ptm.meetings.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Schedule Meeting</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('ptm.meetings') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500 bg-slate-50">
                    <option value="">All statuses</option>
                    @foreach(['Draft', 'Scheduled', 'Notified', 'In progress', 'Completed', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Class</label>
                <select name="class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500 bg-slate-50">
                    <option value="">All classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">From Date</label>
                <input type="date" name="from" value="{{ $from }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500 bg-slate-50">
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">To Date</label>
                <input type="date" name="to" value="{{ $to }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-amber-500 bg-slate-50">
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center justify-center space-x-1 shadow-xs">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Filter</span>
                </button>
                @if($status || $classId || $from || $to)
                <a href="{{ route('ptm.meetings') }}" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-500" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Meetings Schedule Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">PTM Meetings Schedule ({{ $meetings->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Meeting</th>
                        <th class="py-3 px-4">Date / Time</th>
                        <th class="py-3 px-4">Scope</th>
                        <th class="py-3 px-4">Invited</th>
                        <th class="py-3 px-4">Present</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($meetings as $m)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('ptm.meetings.show', $m->id) }}" class="hover:text-amber-600 transition">
                                    {{ $m->title }}
                                </a>
                            </div>
                            <div class="flex items-center space-x-2 mt-0.5 text-[10px] text-slate-400">
                                <span class="capitalize"><i class="fa-solid fa-video text-slate-400 mr-1"></i>{{ str_replace('_', ' ', $m->mode) }}</span>
                                <span>•</span>
                                <span class="truncate max-w-xs">{{ $m->venue ?? 'School Campus' }}</span>
                                @if($m->online_link)
                                    <span>•</span>
                                    <a href="{{ $m->online_link }}" target="_blank" class="text-sky-600 hover:underline flex items-center space-x-0.5">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                        <span>Join Link</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px]">
                            <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }}</div>
                            <div class="text-[10px] text-slate-400">{{ $m->start_time ?? '09:00' }} - {{ $m->end_time ?? '13:00' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            @if($m->scope == 'whole_school')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Whole school</span>
                            @elseif($m->scope == 'class')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">{{ $m->schoolClass->name ?? 'Class' }}</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">Section</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                            {{ $m->attendances_count }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-emerald-600">
                            {{ $m->present_count }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusColors = [
                                    'Draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'Scheduled' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Notified' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'In progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $color = $statusColors[$m->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $color }}">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                @if($m->status != 'Notified' && $m->status != 'Completed')
                                <form action="{{ route('ptm.meetings.notify', $m->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="Broadcast Notification to Guardians">
                                        <i class="fa-solid fa-paper-plane text-[9px]"></i>
                                        <span>Notify</span>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('ptm.meetings.record', $m->id) }}" class="px-2.5 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="Record Attendance & Remarks">
                                    <i class="fa-solid fa-clipboard-user text-[9px]"></i>
                                    <span>Record</span>
                                </a>
                                <a href="{{ route('ptm.meetings.show', $m->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="View Roster">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('ptm.meetings.edit', $m->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="Edit Meeting">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('ptm.meetings.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Remove this PTM meeting?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Meeting">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No meetings match the selected filter criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


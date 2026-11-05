@extends('layouts.app')

@section('title', 'Class Timetable Matrix - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Selection Filter & Print Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('academics.timetable') }}" class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Timetable For:</span>
            
            <div class="flex items-center space-x-1.5">
                <label class="text-xs text-slate-600 font-medium">Class:</label>
                <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-semibold text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-1.5">
                <label class="text-xs text-slate-600 font-medium">Section:</label>
                <select name="section_id" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-semibold text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ $selectedSectionId == $s->id ? 'selected' : '' }}>Section {{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
                <i class="fa-solid fa-print text-indigo-600"></i>
                <span>Print Schedule</span>
            </button>
            <button onclick="document.getElementById('assignSlotModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Schedule Slot</span>
            </button>
        </div>
    </div>

    <!-- Active Class & Section Header Banner -->
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-calendar-week"></i>
            </span>
            <div>
                <h3 class="text-sm font-bold text-indigo-950">
                    Weekly Timetable Grid: {{ $selectedClass ? $selectedClass->name : 'Class' }} - Section {{ $selectedSection ? $selectedSection->name : 'A' }}
                </h3>
                <p class="text-xs text-indigo-700">6 Days Teaching Matrix • Click any cell to schedule or reassign period</p>
            </div>
        </div>
        <div class="text-xs text-indigo-800 font-semibold bg-indigo-100 px-3 py-1 rounded-full">
            Standard 45-Min Bell Schedule
        </div>
    </div>

    <!-- Timetable Matrix Grid -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="p-3.5 w-28 font-bold border border-slate-800 uppercase tracking-wider text-[11px] text-center">
                            Day / Period
                        </th>
                        @foreach($timeSlots as $slot)
                            <th class="p-3 text-center border border-slate-800 min-w-36 {{ $slot->is_break ? 'bg-amber-950/60 text-amber-200' : '' }}">
                                <div class="font-bold text-xs">{{ $slot->name }}</div>
                                <div class="text-[10px] text-slate-300 font-normal mt-0.5">
                                    {{ date('h:i A', strtotime($slot->start_time)) }} - {{ date('h:i A', strtotime($slot->end_time)) }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($days as $day)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Day Name Column -->
                            <td class="p-3.5 font-bold text-slate-800 bg-slate-50 border border-slate-200 text-center uppercase tracking-wide text-xs">
                                {{ substr($day, 0, 3) }}
                                <div class="text-[10px] text-slate-400 font-normal">{{ $day }}</div>
                            </td>

                            <!-- Time Slots Cells -->
                            @foreach($timeSlots as $slot)
                                <td class="p-2 border border-slate-200 align-top {{ $slot->is_break ? 'bg-amber-50/40' : '' }}">
                                    @if($slot->is_break)
                                        <div class="h-full flex flex-col items-center justify-center py-2 text-center text-amber-700">
                                            <i class="fa-solid fa-mug-hot text-xs mb-1"></i>
                                            <span class="text-[10px] font-bold uppercase tracking-wider">{{ $slot->name }}</span>
                                        </div>
                                    @else
                                        @php
                                            $entry = $matrix[$day][$slot->id] ?? null;
                                        @endphp

                                        @if($entry)
                                            <div class="p-2.5 rounded-lg bg-indigo-50/70 border border-indigo-200 hover:border-indigo-400 hover:shadow-xs transition group relative cursor-pointer" onclick="openSlotModal('{{ $day }}', '{{ $slot->id }}', '{{ $entry->subject_id }}', '{{ $entry->teacher_id }}', '{{ $entry->room_number }}')">
                                                <div class="font-bold text-slate-800 text-xs truncate">
                                                    {{ $entry->subject->name ?? 'Subject' }}
                                                </div>
                                                <div class="text-[11px] text-indigo-700 font-medium flex items-center space-x-1 mt-1 truncate">
                                                    <i class="fa-solid fa-chalkboard-user text-[9px]"></i>
                                                    <span>{{ $entry->teacher ? $entry->teacher->first_name . ' ' . $entry->teacher->last_name : 'No Teacher' }}</span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 mt-1 flex items-center justify-between">
                                                    <span>Room {{ $entry->room_number ?? '101' }}</span>
                                                    <span class="opacity-0 group-hover:opacity-100 text-indigo-600 font-bold transition">
                                                        <i class="fa-solid fa-pen-to-square text-[9px]"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <button type="button" onclick="openSlotModal('{{ $day }}', '{{ $slot->id }}')" class="w-full h-full min-h-16 rounded-lg border border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/30 text-slate-300 hover:text-indigo-600 flex flex-col items-center justify-center p-2 transition">
                                                <i class="fa-solid fa-plus text-[10px] mb-0.5"></i>
                                                <span class="text-[10px] font-medium">Assign</span>
                                            </button>
                                        @endif
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

<!-- Modal: Assign / Edit Timetable Slot -->
<div id="assignSlotModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-calendar-plus text-indigo-400"></i>
                <span>Schedule Timetable Slot</span>
            </h4>
            <button onclick="document.getElementById('assignSlotModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.timetable.save-slot') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Day of Week <span class="text-rose-500">*</span></label>
                    <select id="modal_day_of_week" name="day_of_week" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @foreach($days as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Period Slot <span class="text-rose-500">*</span></label>
                    <select id="modal_time_slot_id" name="time_slot_id" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @foreach($timeSlots->where('is_break', 0) as $ts)
                            <option value="{{ $ts->id }}">{{ $ts->name }} ({{ date('h:i A', strtotime($ts->start_time)) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Teaching Subject <span class="text-rose-500">*</span></label>
                <select id="modal_subject_id" name="subject_id" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Choose Subject --</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Faculty / Teacher</label>
                <select id="modal_teacher_id" name="teacher_id" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Select Teacher --</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation ?? 'Faculty' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Classroom / Room Number</label>
                <input type="text" id="modal_room_number" name="room_number" value="101" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('assignSlotModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Save Slot
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSlotModal(day, slotId, subjectId = '', teacherId = '', roomNo = '101') {
    document.getElementById('modal_day_of_week').value = day;
    document.getElementById('modal_time_slot_id').value = slotId;
    if (subjectId) document.getElementById('modal_subject_id').value = subjectId;
    if (teacherId) document.getElementById('modal_teacher_id').value = teacherId;
    if (roomNo) document.getElementById('modal_room_number').value = roomNo;
    document.getElementById('assignSlotModal').classList.remove('hidden');
}
</script>
@endsection

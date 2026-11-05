@extends('layouts.app')

@section('title', 'Exam Schedule & Marks Setup')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

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

    <!-- Exam Selector Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('exams.schedule') }}" class="flex items-center space-x-3 text-xs w-full md:w-auto">
            <label class="font-bold text-slate-700 shrink-0">Select Examination:</label>
            <select name="exam_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs font-semibold">
                @foreach($exams as $ex)
                <option value="{{ $ex->id }}" {{ $selectedExamId == $ex->id ? 'selected' : '' }}>
                    {{ $ex->name }} ({{ $ex->academicSession ? $ex->academicSession->name : '26-27' }})
                </option>
                @endforeach
            </select>
        </form>

        <button onclick="openScheduleModal()" class="px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Add Subject Schedule Slot</span>
        </button>
    </div>

    <!-- Class-wise Schedule Matrix Overview (Matching Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Class-wise Setup Matrix & Status</h3>
                <p class="text-[11px] text-slate-400">Timetable dates, room allocations, and max marks configured per class</p>
            </div>
            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">{{ $classes->count() }} Classes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">Class / Standard</th>
                        <th class="p-3.5">Subjects with Marks Columns</th>
                        <th class="p-3.5">Exam Dates Set</th>
                        <th class="p-3.5">Marks Entered</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($classes as $idx => $cls)
                    @php
                        $classSchedules = $schedules->where('class_id', $cls->id);
                        $totalSubj = $cls->subjects->count() > 0 ? $cls->subjects->count() : 5;
                        $schedCount = $classSchedules->count();
                        $scoredCount = $classSchedules->filter(function($s) { return $s->marks->count() > 0; })->count();
                        $isComplete = $schedCount >= $totalSubj;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 font-bold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $cls->name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $totalSubj }} subjects • {{ $cls->students_count }} students</div>
                        </td>
                        <td class="p-3.5">
                            <div class="font-semibold text-slate-700">{{ $schedCount }} / {{ $totalSubj }}</div>
                            @if(!$isComplete)
                            <span class="text-[10px] text-rose-500">Missing slots</span>
                            @else
                            <span class="text-[10px] text-emerald-600 font-semibold">All subjects set</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $schedCount }} / {{ $totalSubj }}
                        </td>
                        <td class="p-3.5">
                            <div class="flex items-center space-x-1.5">
                                <span class="font-bold text-slate-800">{{ $schedCount > 0 ? round(($scoredCount / $schedCount)*100) : 0 }}%</span>
                                <span class="text-[10px] text-slate-400">({{ $scoredCount }} scored)</span>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isComplete ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $isComplete ? 'CONFIGURED' : 'PENDING SLOTS' }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <button onclick="openScheduleModalForClass({{ $cls->id }}, '{{ addslashes($cls->name) }}')" class="px-2.5 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded text-[11px] font-semibold transition">
                                + Add Slot
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Configured Timetable Slots Master Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Scheduled Examination Timetable Slots ({{ $schedules->count() }})</h4>
            <span class="text-[10px] text-slate-400">Active Exam: {{ $selectedExam ? $selectedExam->name : 'N/A' }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3">Class</th>
                        <th class="p-3">Subject</th>
                        <th class="p-3">Exam Date</th>
                        <th class="p-3">Time</th>
                        <th class="p-3">Room</th>
                        <th class="p-3">Max Marks</th>
                        <th class="p-3">Pass Marks</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $sched)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3 font-bold text-slate-800">{{ $sched->schoolClass ? $sched->schoolClass->name : 'Class ' . $sched->class_id }}</td>
                        <td class="p-3 font-semibold text-purple-700">{{ $sched->subject ? $sched->subject->name : 'Subject ' . $sched->subject_id }}</td>
                        <td class="p-3 text-slate-700">{{ \Carbon\Carbon::parse($sched->exam_date)->format('D, d M Y') }}</td>
                        <td class="p-3 text-slate-600">{{ $sched->start_time ? substr($sched->start_time, 0, 5) : '09:00' }} - {{ $sched->end_time ? substr($sched->end_time, 0, 5) : '12:00' }}</td>
                        <td class="p-3 font-medium text-slate-600">{{ $sched->room_no ?? 'Hall A' }}</td>
                        <td class="p-3 font-bold text-slate-800">{{ $sched->max_marks }}</td>
                        <td class="p-3 text-emerald-600 font-bold">{{ $sched->pass_marks }}</td>
                        <td class="p-3 text-right">
                            <form action="{{ route('exams.schedule.delete', $sched->id) }}" method="POST" onsubmit="return confirm('Remove this timetable slot?');" class="inline">
                                @csrf
                                <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Delete Slot">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400">No subject slots scheduled yet for this examination.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Subject Schedule Slot Modal -->
<div id="scheduleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="px-6 py-4 bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold">Schedule Subject Timetable Slot</h3>
                <p class="text-[11px] text-slate-400">Assign exam date, time window, room, and pass/max marks</p>
            </div>
            <button onclick="closeScheduleModal()" class="text-slate-400 hover:text-white transition p-1 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('exams.schedule.store') }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $selectedExamId }}">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Target Class <span class="text-rose-500">*</span></label>
                    <select name="class_id" id="modalClassSelect" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                        @foreach($allSubjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code ?? 'SUB' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Exam Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="exam_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Room / Hall No</label>
                    <input type="text" name="room_no" placeholder="e.g. Exam Hall B / Room 204" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" value="09:00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">End Time</label>
                    <input type="time" name="end_time" value="12:00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Max Marks <span class="text-rose-500">*</span></label>
                    <input type="number" name="max_marks" required value="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Pass Marks <span class="text-rose-500">*</span></label>
                    <input type="number" name="pass_marks" required value="33" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="closeScheduleModal()" class="px-3.5 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition">Cancel</button>
                <button type="submit" class="px-4 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">Save Schedule Slot</button>
            </div>
        </form>
    </div>
</div>

<script>
function openScheduleModal() {
    document.getElementById('scheduleModal').classList.remove('hidden');
}
function openScheduleModalForClass(classId, className) {
    document.getElementById('modalClassSelect').value = classId;
    document.getElementById('scheduleModal').classList.remove('hidden');
}
function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
}
</script>
@endsection


@extends('layouts.app')

@section('title', 'Daily Attendance Register')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Daily Attendance Register</h1>
            <p class="text-xs text-slate-500">Record daily classroom presence, late punches, and verified leaves</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="markAll('present')" class="inline-flex items-center space-x-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-2 rounded-xl border border-emerald-200 transition">
                <i class="fa-solid fa-check-double text-xs"></i>
                <span>Mark All Present</span>
            </button>
            <button type="button" onclick="markAll('absent')" class="inline-flex items-center space-x-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold px-3 py-2 rounded-xl border border-rose-200 transition">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span>Mark All Absent</span>
            </button>
        </div>
    </div>

    <!-- Class & Date Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Class *</label>
                <select name="class_id" required class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Section</label>
                <select name="section_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">All Sections</option>
                    <option value="1" {{ $selectedSectionId == 1 ? 'selected' : '' }}>Section A</option>
                    <option value="2" {{ $selectedSectionId == 2 ? 'selected' : '' }}>Section B</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Date *</label>
                <input type="date" name="date" value="{{ $date }}" required class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                    Load Class Register
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Form -->
    @if($students->isNotEmpty())
        <form method="POST" action="{{ route('attendance.store') }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="text-xs font-bold text-slate-800">
                    Roll Call List ({{ $students->count() }} Enrolled Students) • Date: {{ date('d M Y', strtotime($date)) }}
                </div>
                <div class="flex items-center space-x-3 text-xs font-semibold text-slate-500">
                    <span class="flex items-center space-x-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> <span>P: Present</span></span>
                    <span class="flex items-center space-x-1"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> <span>A: Absent</span></span>
                    <span class="flex items-center space-x-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> <span>L: Late</span></span>
                    <span class="flex items-center space-x-1"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> <span>H: Half Day</span></span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                            <th class="py-3 px-4 w-16">Roll</th>
                            <th class="py-3 px-4">Student Name</th>
                            <th class="py-3 px-4">Admission No</th>
                            <th class="py-3 px-4 text-center">Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $st)
                            @php
                                $status = $attendanceMap[$st->id] ?? 'present';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $st->roll_no ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $st->full_name }}</div>
                                    <div class="text-[10px] text-slate-400">Class: {{ $st->latestEnrollment->schoolClass->name ?? '' }} ({{ $st->latestEnrollment->section->name ?? 'A' }})</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-600">{{ $st->admission_no }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-4">
                                        <label class="flex items-center space-x-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}]" value="present" {{ $status == 'present' ? 'checked' : '' }} class="att-radio-present text-emerald-600 focus:ring-emerald-500">
                                            <span class="font-semibold text-emerald-700 text-xs">Present</span>
                                        </label>
                                        <label class="flex items-center space-x-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}]" value="absent" {{ $status == 'absent' ? 'checked' : '' }} class="att-radio-absent text-rose-600 focus:ring-rose-500">
                                            <span class="font-semibold text-rose-700 text-xs">Absent</span>
                                        </label>
                                        <label class="flex items-center space-x-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}]" value="late" {{ $status == 'late' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                            <span class="font-semibold text-amber-700 text-xs">Late</span>
                                        </label>
                                        <label class="flex items-center space-x-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}]" value="half_day" {{ $status == 'half_day' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                                            <span class="font-semibold text-purple-700 text-xs">Half Day</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-save"></i>
                    <span>Save Daily Attendance</span>
                </button>
            </div>
        </form>
    @else
        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center text-slate-400">
            <i class="fa-solid fa-users-slash text-3xl mb-2 text-slate-300"></i>
            <p class="text-xs">No students found in the selected class. Please select another class from the filter above.</p>
        </div>
    @endif

</div>

<script>
function markAll(status) {
    if (status === 'present') {
        document.querySelectorAll('.att-radio-present').forEach(r => r.checked = true);
    } else if (status === 'absent') {
        document.querySelectorAll('.att-radio-absent').forEach(r => r.checked = true);
    }
}
</script>
@endsection


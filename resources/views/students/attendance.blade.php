@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-clipboard-user text-amber-600"></i>
                <span>Student Attendance Register</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Daily roll call by Class & Section with instant absence alerts</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="bg-amber-50 text-amber-700 font-extrabold px-3 py-1.5 rounded-xl border border-amber-200">
                Register Date: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </span>
        </div>
    </div>

    <!-- Cohort Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('students.attendance') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Class <span class="text-red-500">*</span></label>
                <select name="class_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-amber-500">
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Section</label>
                <select name="section_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-amber-500">
                    <option value="">All Sections</option>
                    <option value="1" {{ $selectedSectionId == 1 ? 'selected' : '' }}>Section A</option>
                    <option value="2" {{ $selectedSectionId == 2 ? 'selected' : '' }}>Section B</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Attendance Date</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-amber-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg transition">
                    Load Register
                </button>
            </div>
        </form>
    </div>

    @if(count($students) > 0)
    <!-- Attendance Marking Form -->
    <form method="POST" action="{{ route('students.attendance.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-xs text-slate-800">Class Attendance Sheet ({{ count($students) }} Students)</h3>
                    <p class="text-[10px] text-slate-400">Select status for each candidate or use quick preset</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="document.querySelectorAll('.radio-present').forEach(r => r.checked = true);" class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1 rounded-lg transition">
                        ✓ Mark All Present
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto text-xs">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 w-12">Roll</th>
                            <th class="py-3 px-4">Adm No</th>
                            <th class="py-3 px-4">Student Name</th>
                            <th class="py-3 px-4 text-center">Status (P / L / A / H)</th>
                            <th class="py-3 px-4">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $st)
                        @php
                            $att = $st->attendances->first();
                            $currentStatus = $att ? $att->status : 'present';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-700">{{ $st->roll_no ?? '-' }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">{{ $st->admission_no }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">
                                {{ $st->first_name }} {{ $st->last_name }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center justify-center space-x-4">
                                    <label class="flex items-center space-x-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="present" class="radio-present text-emerald-600 focus:ring-emerald-500" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                        <span class="font-bold text-[11px] text-emerald-700">P</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="late" class="text-amber-600 focus:ring-amber-500" {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                        <span class="font-bold text-[11px] text-amber-700">L</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="absent" class="text-rose-600 focus:ring-rose-500" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                        <span class="font-bold text-[11px] text-rose-700">A</span>
                                    </label>
                                    <label class="flex items-center space-x-1 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $st->id }}]" value="half_day" class="text-purple-600 focus:ring-purple-500" {{ $currentStatus == 'half_day' ? 'checked' : '' }}>
                                        <span class="font-bold text-[11px] text-purple-700">H</span>
                                    </label>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="remarks[{{ $st->id }}]" value="{{ $att->remarks ?? '' }}" placeholder="Optional remark..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-xs focus:outline-blue-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5 text-xs">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Save Daily Attendance</span>
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white p-12 text-center text-slate-400 rounded-2xl border border-slate-200 text-xs">
        Select a Class & Section above to load the attendance register roster.
    </div>
    @endif
</div>
@endsection


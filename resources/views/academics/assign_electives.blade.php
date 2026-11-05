@extends('layouts.app')

@section('title', 'Assign Elective Subjects - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Selection Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
        <div class="mb-3">
            <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i>
                <span>Select Class, Section & Elective Choice</span>
            </h3>
            <p class="text-xs text-slate-500">Pick which elective subject students have opted for under NEP 2020 multi-disciplinary choices</p>
        </div>

        <form method="GET" action="{{ route('academics.assign-electives') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Class</label>
                <select name="class_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Section</label>
                <select name="section_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ $selectedSectionId == $s->id ? 'selected' : '' }}>Section {{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Elective Subject</label>
                <select name="subject_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($electives as $el)
                        <option value="{{ $el->id }}" {{ $selectedSubjectId == $el->id ? 'selected' : '' }}>{{ $el->name }} ({{ $el->code }})</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Student Selection Form & Checklist Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <form method="POST" action="{{ route('academics.assign-electives.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-800">Student Roll Checklist</span>
                    <span class="text-xs text-slate-500">({{ $students->count() }} students in section)</span>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="document.querySelectorAll('.student-checkbox').forEach(c => c.checked = true)" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                        Select All
                    </button>
                    <span class="text-slate-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('.student-checkbox').forEach(c => c.checked = false)" class="text-xs text-slate-500 hover:text-slate-700 font-medium">
                        Deselect All
                    </button>
                    <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Assign Elective</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-slate-700 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-3.5 w-12 text-center">
                                <input type="checkbox" onchange="document.querySelectorAll('.student-checkbox').forEach(c => c.checked = this.checked)" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="p-3.5">Admission No</th>
                            <th class="p-3.5">Roll No</th>
                            <th class="p-3.5">Student Name</th>
                            <th class="p-3.5">Gender</th>
                            <th class="p-3.5 text-center">Current Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $st)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-3.5 text-center">
                                    <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" class="student-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="p-3.5 font-mono font-medium text-slate-700">
                                    {{ $st->admission_no ?? 'ADM-' . $st->id }}
                                </td>
                                <td class="p-3.5 font-bold text-slate-800">
                                    #{{ $st->roll_no ?? $loop->iteration }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-800">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($st->first_name, 0, 1) }}
                                        </div>
                                        <span>{{ $st->first_name }} {{ $st->last_name }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 text-slate-500 capitalize">
                                    {{ $st->gender ?? 'N/A' }}
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                        Active Enrolled
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">
                                    No students enrolled in this section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
@endsection


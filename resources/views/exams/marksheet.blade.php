@extends('layouts.app')

@section('title', 'Generate Student Marksheet')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

    <!-- Criteria Selection Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Select Report Card Criteria</h3>
        <form method="GET" action="{{ route('exams.marksheet.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
            <!-- Exam -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Examination <span class="text-rose-500">*</span></label>
                <select name="exam_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">-- Select Exam --</option>
                    @foreach($exams as $ex)
                    <option value="{{ $ex->id }}" {{ $examId == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Class / Standard <span class="text-rose-500">*</span></label>
                <select name="class_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Section</label>
                <select name="section_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">All Sections</option>
                    @foreach($sections as $sec)
                    <option value="{{ $sec->id }}" {{ $sectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-xs flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-file-invoice text-xs"></i>
                    <span>Generate Marksheets</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Student Marksheets Directory Table -->
    @if($classId)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Class Marksheets Roster</h3>
                <p class="text-[11px] text-slate-500">Official CBSE Progress Report Cards available for individual or batch dispatch</p>
            </div>
            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg text-xs">
                {{ $students->count() }} Candidates
            </span>
        </div>

        @if($students->count() == 0)
        <div class="p-12 text-center text-slate-400 text-xs">
            <p>No students enrolled in the selected class.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">Roll No</th>
                        <th class="p-3.5">Admission No</th>
                        <th class="p-3.5">Candidate / Student</th>
                        <th class="p-3.5">Class & Section</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $idx => $st)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 font-bold text-slate-500">{{ $st->roll_number ?? ($idx + 1) }}</td>
                        <td class="p-3.5 font-mono text-[11px] text-slate-600">{{ $st->admission_number }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $st->full_name }}</div>
                            <div class="text-[10px] text-slate-400">Parent: {{ $st->guardian->father_name ?? 'Parent' }}</div>
                        </td>
                        <td class="p-3.5 font-medium text-slate-700">
                            {{ $st->latestEnrollment->schoolClass->name ?? 'Class' }} ({{ $st->latestEnrollment->section->name ?? 'A' }})
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                GENERATED
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('exams.marksheet.show', [$st->id, $examId]) }}" target="_blank" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg text-[11px] transition shadow-xs flex items-center space-x-1">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    <span>View / Print</span>
                                </a>
                                <a href="{{ route('exams.admit-cards.print', [$st->id, $examId]) }}" target="_blank" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px] transition" title="Print Admit Card">
                                    <i class="fa-solid fa-id-card"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center shadow-xs text-slate-400">
        <i class="fa-solid fa-file-invoice text-3xl mb-2 text-purple-300"></i>
        <h4 class="font-bold text-slate-700 text-sm">Select Class & Examination Above to View Report Cards</h4>
    </div>
    @endif
</div>
@endsection

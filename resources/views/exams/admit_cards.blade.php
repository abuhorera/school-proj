@extends('layouts.app')

@section('title', 'Exam Admit Cards (Hall Tickets)')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

    <!-- Criteria Selection Card (Exact match to live demo) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Select Hall Ticket Criteria</h3>
        <form method="GET" action="{{ route('exams.admit-cards') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
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
                    <i class="fa-solid fa-id-card text-xs"></i>
                    <span>Fetch Hall Tickets</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Candidates Hall Tickets Table -->
    @if($classId && $examId)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-purple-50/40">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">
                    Admit Cards / Hall Tickets Register
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Exam: <span class="font-semibold text-purple-700">{{ $selectedExam->name ?? 'Examination' }}</span> • 
                    Schedules: <span class="font-semibold text-slate-700">{{ $schedules->count() }} Papers scheduled</span>
                </p>
            </div>

            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg text-xs">
                    {{ $students->count() }} Candidates
                </span>
            </div>
        </div>

        @if($students->count() == 0)
        <div class="p-12 text-center text-slate-400 text-xs">
            <p>No students found for selected criteria.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">Roll No</th>
                        <th class="p-3.5">Hall Ticket No</th>
                        <th class="p-3.5">Admission No</th>
                        <th class="p-3.5">Student / Candidate</th>
                        <th class="p-3.5">Class & Section</th>
                        <th class="p-3.5">Papers Scheduled</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $idx => $st)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 font-bold text-slate-500">{{ $st->roll_number ?? ($idx + 1) }}</td>
                        <td class="p-3.5 font-mono text-[11px] font-bold text-purple-700">HT-26-{{ str_pad($st->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3.5 font-mono text-[11px] text-slate-600">{{ $st->admission_number }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $st->full_name }}</div>
                            <div class="text-[10px] text-slate-400">Parent: {{ $st->guardian->father_name ?? 'Parent' }}</div>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $st->latestEnrollment->schoolClass->name ?? 'Standard' }} ({{ $st->latestEnrollment->section->name ?? 'A' }})
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $schedules->count() }} Papers
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('exams.admit-cards.print', [$st->id, $examId]) }}" target="_blank" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg text-[11px] transition shadow-xs inline-flex items-center space-x-1">
                                <i class="fa-solid fa-print text-[10px]"></i>
                                <span>Print Admit Card</span>
                            </a>
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
        <i class="fa-solid fa-id-card text-3xl mb-2 text-purple-300"></i>
        <h4 class="font-bold text-slate-700 text-sm">Select Examination and Class Above to Generate Admit Cards</h4>
    </div>
    @endif
</div>
@endsection


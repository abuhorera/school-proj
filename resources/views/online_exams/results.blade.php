@extends('layouts.app')

@section('title', 'Exam Results: ' . $exam->title)

@section('content')
<div class="space-y-6">
    @include('online_exams.partials.subnav')

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

    <!-- Exam Particulars Banner -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                    CBT Results & Submissions
                </span>
                <span class="text-xs text-slate-400 font-mono">Max Marks: {{ $exam->total_marks }}</span>
            </div>
            <h1 class="text-xl font-black text-slate-900">{{ $exam->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $exam->schoolClass->name ?? 'Class' }} ({{ $exam->section->name ?? 'A' }}) • {{ $exam->subject->name ?? 'Subject' }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('online-exams.builder', $exam->id) }}" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-pen-ruler"></i>
                <span>Builder</span>
            </a>
            <a href="{{ route('online-exams.take', $exam->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-play text-xs"></i>
                <span>CBT Simulator</span>
            </a>
            <a href="{{ route('online-exams.manage') }}" class="p-2 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-xl text-xs transition" title="Back to Exams">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Submissions</span>
            <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $attempts->count() }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Passing Rate</span>
            @php
                $passCount = $attempts->where('status', 'Passed')->count();
                $passRate = $attempts->count() > 0 ? round(($passCount / $attempts->count()) * 100, 1) : 0;
            @endphp
            <h4 class="text-2xl font-black text-emerald-600 mt-1">{{ $passRate }}%</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Average Score</span>
            @php
                $avgScore = $attempts->count() > 0 ? round($attempts->avg('score_obtained'), 1) : 0;
            @endphp
            <h4 class="text-2xl font-black text-blue-600 mt-1">{{ $avgScore }} <span class="text-xs text-slate-400 font-normal">/ {{ $exam->total_marks }}</span></h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-2xs">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Highest Score</span>
            @php
                $maxScore = $attempts->max('score_obtained') ?? 0;
            @endphp
            <h4 class="text-2xl font-black text-purple-600 mt-1">{{ $maxScore }}</h4>
        </div>
    </div>

    <!-- Student Results Table (Exact match to Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Student Exam Submissions Roster</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Admission No</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Score Obtained</th>
                        <th class="py-3 px-4">Percentage</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Submitted At</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($attempts as $att)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                            {{ $att->student->admission_number ?? 'ST-'.$att->student_id }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">{{ $att->student->first_name ?? 'Student' }} {{ $att->student->last_name ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $att->score_obtained }} <span class="text-[10px] text-slate-400 font-normal">/ {{ $exam->total_marks }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold">{{ $att->percentage }}%</span>
                                <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $att->percentage >= $exam->passing_percentage ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ min(100, $att->percentage) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $att->status == 'Passed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                {{ $att->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                            {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('online-exams.answer-sheet', ['examId' => $exam->id, 'attemptId' => $att->id]) }}" class="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold rounded-lg text-[11px] transition inline-flex items-center space-x-1">
                                <i class="fa-solid fa-file-lines text-[10px]"></i>
                                <span>Answer Sheet</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-users-slash text-2xl text-slate-300 mb-1"></i>
                            <p>No student submissions recorded for this online exam yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


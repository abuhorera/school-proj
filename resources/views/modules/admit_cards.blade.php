@extends('layouts.app')

@section('title', 'Examination Admit Cards')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Offline Examinations</div>
            <h1 class="text-2xl font-bold text-slate-800">Student Admit Cards & Hall Tickets</h1>
            <p class="text-xs text-slate-500 mt-1">Generate official exam admit cards with student photos, exam schedules, and roll numbers.</p>
        </div>
        <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-print"></i>
            <span>Bulk Print Admit Cards</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($students as $idx => $st)
        <div class="bg-white rounded-xl border-2 border-slate-300 p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-700 text-white flex items-center justify-center font-bold text-sm">
                        YIS
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-800 leading-none">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</h4>
                        <span class="text-[10px] text-slate-500">Mid Term Examination 2026-27</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[9px] uppercase font-bold text-slate-400">Roll No</span>
                    <div class="font-mono font-bold text-xs text-slate-800">2026-{{ 100 + $idx }}</div>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-16 h-20 rounded bg-slate-100 border border-slate-300 flex items-center justify-center text-slate-400 text-xs shrink-0 font-medium">
                    PHOTO
                </div>
                <div class="flex-1 text-xs space-y-1">
                    <div class="font-bold text-sm text-slate-800">{{ $st->full_name }}</div>
                    <div class="text-slate-600">Admission No: <span class="font-mono font-semibold">{{ $st->admission_number }}</span></div>
                    <div class="text-slate-600">Class & Section: <span class="font-semibold">Class 10 - Section A</span></div>
                    <div class="text-slate-600">Exam Center: <span class="font-semibold">YIS Main Hall (Block A)</span></div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Principal Signature Verified</span>
                <span class="text-purple-600 font-bold">Print Pass →</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Online Examinations')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">CBT & Digital Testing</div>
            <h1 class="text-2xl font-bold text-slate-800">Online Examination Portal</h1>
            <p class="text-xs text-slate-500 mt-1">Computer-based tests, timed MCQ assessments, auto-grading, and question bank.</p>
        </div>
        <button onclick="alert('Create online quiz')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Create Online Exam</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($exams as $e)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $e['status'] == 'Live' ? 'bg-red-50 text-red-600 border border-red-200 animate-pulse' : 'bg-slate-100 text-slate-600' }}">
                        ● {{ $e['status'] }}
                    </span>
                    <span class="text-xs text-slate-500 font-semibold">{{ $e['duration'] }}</span>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mt-1">{{ $e['title'] }}</h3>
                <p class="text-xs text-slate-500 mt-1">Target: {{ $e['class'] }} • {{ $e['questions'] }} Questions</p>
            </div>
            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Auto-grading: Active</span>
                <button class="text-blue-600 hover:text-blue-800 font-bold">Manage Test →</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


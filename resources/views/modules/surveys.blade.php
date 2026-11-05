@extends('layouts.app')

@section('title', 'Surveys & Feedback')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Feedback Triage</div>
            <h1 class="text-2xl font-bold text-slate-800">Stakeholder Surveys & Feedback</h1>
            <p class="text-xs text-slate-500 mt-1">Collect feedback from parents, teachers, and students to improve school standards.</p>
        </div>
        <button onclick="alert('Create survey')" class="bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Create New Survey</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($surveys as $s)
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $s['status'] == 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                        {{ $s['status'] }}
                    </span>
                    <span class="text-xs font-bold text-amber-600 flex items-center space-x-1">
                        <i class="fa-solid fa-star text-[10px]"></i>
                        <span>{{ $s['rating'] }}</span>
                    </span>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mt-1">{{ $s['title'] }}</h3>
                <p class="text-xs text-slate-500 mt-1">Target Audience: {{ $s['target'] }}</p>
                <div class="mt-3 text-xs text-slate-600 font-medium">
                    Total Responses Received: <strong class="text-slate-800">{{ $s['responses'] }}</strong>
                </div>
            </div>
            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400">Anonymous Responses</span>
                <button class="text-amber-600 hover:text-amber-800 font-bold">Analytics & Report →</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


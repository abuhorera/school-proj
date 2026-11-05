@extends('layouts.app')

@section('title', 'Exam Types Setup')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Offline Examinations</div>
            <h1 class="text-2xl font-bold text-slate-800">Examination Types & Weightage</h1>
            <p class="text-xs text-slate-500 mt-1">Configure Unit Tests, Term Examinations, and Final Board pre-board weightages.</p>
        </div>
        <button onclick="alert('Add exam type')" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Exam Type</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($types as $t)
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="font-mono font-bold text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-200">
                    {{ $t['code'] }}
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $t['status'] == 'Active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $t['status'] }}
                </span>
            </div>
            <h3 class="font-bold text-sm text-slate-800 mt-3">{{ $t['name'] }}</h3>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Weightage: <strong class="text-slate-800">{{ $t['weightage'] }}</strong></span>
                <button class="text-purple-600 hover:text-purple-800 font-semibold">Edit</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Hero Banner Slides')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">School Website</div>
            <h1 class="text-2xl font-bold text-slate-800">Hero Banner Slides</h1>
            <p class="text-xs text-slate-500 mt-1">Homepage banner carousels, call-to-action buttons, and promotional announcements.</p>
        </div>
        <button onclick="alert('Add slide')" class="bg-purple-600 hover:bg-purple-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Banner Slide</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($slides as $s)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="h-36 bg-gradient-to-tr from-slate-900 to-indigo-900 p-4 flex flex-col justify-between text-white">
                <span class="text-[10px] font-bold bg-white/20 px-2 py-0.5 rounded-full w-max">Slide #{{ $s['order'] }}</span>
                <h4 class="font-bold text-sm leading-snug">{{ $s['title'] }}</h4>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between text-xs">
                <p class="text-slate-600 mb-3">{{ $s['subtitle'] }}</p>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-semibold text-purple-600">Button: {{ $s['btn_text'] }}</span>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded font-bold">{{ $s['status'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


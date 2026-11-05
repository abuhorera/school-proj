@extends('layouts.app')

@section('title', 'Campus Facilities')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">School Website</div>
            <h1 class="text-2xl font-bold text-slate-800">Campus Facilities & Infrastructure</h1>
            <p class="text-xs text-slate-500 mt-1">Showcase laboratories, athletic grounds, libraries, and smart facilities to prospective parents.</p>
        </div>
        <button onclick="alert('Add facility')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Campus Facility</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($facilities as $f)
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-start space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">{{ $f['name'] }}</h3>
                    <span class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded">{{ $f['category'] }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">{{ $f['desc'] }}</p>
                <div class="mt-3 flex items-center space-x-3 text-xs">
                    <a href="#" class="text-emerald-600 hover:text-emerald-800 font-semibold">Edit Details</a>
                    <span class="text-slate-300">•</span>
                    <a href="#" class="text-slate-500 hover:text-slate-700">Upload Photos</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


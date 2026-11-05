@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">School Website</div>
            <h1 class="text-2xl font-bold text-slate-800">Parent & Alumni Testimonials</h1>
            <p class="text-xs text-slate-500 mt-1">Manage verified parent reviews, success stories, and alumni feedback.</p>
        </div>
        <button onclick="alert('Add testimonial')" class="bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Testimonial</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($testimonials as $t)
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-1 text-amber-400 text-xs mb-3">
                    @for($i=0; $i<$t['rating']; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                </div>
                <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $t['content'] }}"</p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs">
                    {{ substr($t['parent'], 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-800">{{ $t['parent'] }}</h4>
                    <span class="text-[10px] text-slate-400">Parent of {{ $t['child'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


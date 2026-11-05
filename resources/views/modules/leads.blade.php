@extends('layouts.app')

@section('title', 'Lead Management & Pipeline')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-cyan-600 uppercase tracking-wider mb-1">CRM & Admissions</div>
            <h1 class="text-2xl font-bold text-slate-800">Admission Leads & Kanban Pipeline</h1>
            <p class="text-xs text-slate-500 mt-1">Track prospective student enquiries from first contact to enrollment confirmation.</p>
        </div>
        <button onclick="alert('Add enquiry modal')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Lead</span>
        </button>
    </div>

    <!-- Kanban Columns -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($stages as $stageName => $items)
        <div class="bg-slate-100 rounded-xl p-3 border border-slate-200 flex flex-col">
            <div class="flex items-center justify-between pb-2.5 mb-2 border-b border-slate-200">
                <span class="font-bold text-xs text-slate-700">{{ $stageName }}</span>
                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center justify-center">
                    {{ $items ? $items->count() : 0 }}
                </span>
            </div>
            <div class="space-y-2 flex-1">
                @if($items && $items->count() > 0)
                    @foreach($items as $lead)
                    <div class="bg-white p-3.5 rounded-lg border border-slate-200 shadow-sm text-xs space-y-1.5 hover:border-cyan-400 transition cursor-pointer">
                        <div class="font-bold text-slate-800">{{ $lead->name ?? 'Enquiry #' . $lead->id }}</div>
                        <div class="text-slate-500 text-[11px]">Class: {{ $lead->class_applying_for ?? 'Class 1' }}</div>
                        <div class="text-slate-600 font-medium flex items-center space-x-1">
                            <i class="fa-solid fa-phone text-slate-400 text-[10px]"></i>
                            <span>{{ $lead->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="text-[10px] text-slate-400 pt-1 border-t border-slate-100 flex items-center justify-between">
                            <span>Source: {{ $lead->source ?? 'Walk-in' }}</span>
                            <span class="text-cyan-600 font-semibold">Details →</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-6 text-slate-400 text-xs italic">
                        No leads in this stage
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

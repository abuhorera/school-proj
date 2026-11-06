@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-2xl mx-auto">
    @include('transport.nav')

    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-route text-amber-500"></i>
                Create New Transit Route
            </span>
            <a href="{{ route('school.transport.routes') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Routes</a>
        </div>

        <form action="{{ route('school.transport.routes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Route Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Route A — Shankar Nagar, Route B — Telibandha" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Monthly Default Fare (₹) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" min="0" name="fare" value="1200.00" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500 font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Assign Fleet Vehicle (Optional)</label>
                <select name="vehicle_id" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Assign Later / No Vehicle --</option>
                    @foreach($vehicles as $veh)
                    <option value="{{ $veh->id }}">{{ $veh->vehicle_no }} ({{ $veh->vehicle_model }} - {{ $veh->seating_capacity }} seats)</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.transport.routes') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-amber-500 text-slate-900 hover:bg-amber-600 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Create Route & Configure Stops
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


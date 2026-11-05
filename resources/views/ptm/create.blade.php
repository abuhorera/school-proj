@extends('layouts.app')

@section('title', 'Schedule PTM - Parent Teacher Meetings')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('ptm.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-calendar-plus"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Schedule Parent–Teacher Meeting</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Set conference agenda, target class/scope, timing slots, and invite guardian roster</p>
        </div>
        <a href="{{ route('ptm.meetings') }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
            Back to Meetings
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('ptm.meetings.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Meeting Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Title / Agenda <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Term 1 Academic Progress & Holistic Development Review" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
            </div>

            <!-- Date & Time Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="meeting_date" required value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" value="09:00" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">End Time</label>
                    <input type="time" name="end_time" value="13:00" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Mode & Scope Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Conference Mode <span class="text-rose-500">*</span></label>
                    <select name="mode" id="modeSelect" required onchange="toggleModeFields()" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="in_person">In-Person (On Campus)</option>
                        <option value="online">Online (Video Conference)</option>
                        <option value="hybrid">Hybrid (Both)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Scope <span class="text-rose-500">*</span></label>
                    <select name="scope" id="scopeSelect" required onchange="toggleScopeFields()" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="whole_school">Whole School</option>
                        <option value="class">Specific Class</option>
                        <option value="section">Specific Section</option>
                    </select>
                </div>
                <div id="classFieldContainer" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Class</label>
                    <select name="school_class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Venue & Online Link -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="venueField">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Campus Venue / Location</label>
                    <input type="text" name="venue" placeholder="e.g. School Auditorium & Respective Classrooms" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div id="onlineLinkField">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Online Meeting Link (Google Meet / Zoom)</label>
                    <input type="url" name="online_link" placeholder="https://meet.google.com/xyz-ptm" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Description / Agenda Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detailed Agenda & Instructions for Parents</label>
                <textarea name="description" rows="3.5" placeholder="Specify key focus points (e.g. formative test analysis, CBC progress reports, behavioral observations, 15-minute slot allocations)..." class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <!-- Auto Roster Generation Checkbox -->
            <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200/80 flex items-start space-x-3">
                <input type="checkbox" name="generate_roster" id="generate_roster" value="1" checked class="mt-0.5 rounded border-slate-300 text-amber-600 focus:ring-amber-500 w-4 h-4">
                <label for="generate_roster" class="text-xs text-slate-700 cursor-pointer">
                    <span class="font-bold text-slate-900 block">Auto-generate student & guardian attendance roster</span>
                    <span class="text-slate-500 text-[11px]">Automatically invite all eligible students and guardians according to the selected scope and create default 15-minute conference time slots.</span>
                </label>
            </div>

            <!-- Submit Button Row -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <a href="{{ route('ptm.meetings') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Schedule PTM</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleScopeFields() {
    const scope = document.getElementById('scopeSelect').value;
    const classContainer = document.getElementById('classFieldContainer');
    if (scope === 'class' || scope === 'section') {
        classContainer.classList.remove('hidden');
    } else {
        classContainer.classList.add('hidden');
    }
}
function toggleModeFields() {
    const mode = document.getElementById('modeSelect').value;
    const venueField = document.getElementById('venueField');
    const onlineField = document.getElementById('onlineLinkField');
    if (mode === 'online') {
        onlineField.classList.remove('hidden');
    } else if (mode === 'in_person') {
        onlineField.classList.add('hidden');
    } else {
        onlineField.classList.remove('hidden');
        venueField.classList.remove('hidden');
    }
}
</script>
@endsection


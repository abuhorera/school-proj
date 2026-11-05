@extends('layouts.app')

@section('title', 'Edit PTM — ' . $meeting->title)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('ptm.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Edit PTM Meeting</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Modify meeting timing, mode, venue or update status</p>
        </div>
        <a href="{{ route('ptm.meetings') }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
            Back to Meetings
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('ptm.meetings.update', $meeting->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Meeting Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Title / Agenda <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ $meeting->title }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
            </div>

            <!-- Date, Time & Status Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="meeting_date" required value="{{ $meeting->meeting_date }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" value="{{ $meeting->start_time ?? '09:00' }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">End Time</label>
                    <input type="time" name="end_time" value="{{ $meeting->end_time ?? '13:00' }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white font-bold text-amber-700">
                        @foreach(['Draft', 'Scheduled', 'Notified', 'In progress', 'Completed', 'Cancelled'] as $st)
                            <option value="{{ $st }}" {{ $meeting->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Mode & Scope Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Conference Mode <span class="text-rose-500">*</span></label>
                    <select name="mode" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="in_person" {{ $meeting->mode == 'in_person' ? 'selected' : '' }}>In-Person (On Campus)</option>
                        <option value="online" {{ $meeting->mode == 'online' ? 'selected' : '' }}>Online (Video Conference)</option>
                        <option value="hybrid" {{ $meeting->mode == 'hybrid' ? 'selected' : '' }}>Hybrid (Both)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meeting Scope <span class="text-rose-500">*</span></label>
                    <select name="scope" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="whole_school" {{ $meeting->scope == 'whole_school' ? 'selected' : '' }}>Whole School</option>
                        <option value="class" {{ $meeting->scope == 'class' ? 'selected' : '' }}>Specific Class</option>
                        <option value="section" {{ $meeting->scope == 'section' ? 'selected' : '' }}>Specific Section</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Class</label>
                    <select name="school_class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="">-- All Classes --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ ($meeting->school_class_id == $c->id || $meeting->class_id == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Venue & Online Link -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Campus Venue / Location</label>
                    <input type="text" name="venue" value="{{ $meeting->venue }}" placeholder="e.g. School Auditorium & Respective Classrooms" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Online Meeting Link (Google Meet / Zoom)</label>
                    <input type="url" name="online_link" value="{{ $meeting->online_link }}" placeholder="https://meet.google.com/xyz-ptm" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Description / Agenda Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Detailed Agenda & Instructions for Parents</label>
                <textarea name="description" rows="3.5" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-amber-500">{{ $meeting->description }}</textarea>
            </div>

            <!-- Submit Button Row -->
            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <a href="{{ route('ptm.meetings') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Update Meeting</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


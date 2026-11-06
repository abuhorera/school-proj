@extends('layouts.app')

@section('title', 'Edit Event / Holiday')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('school.events') }}" class="hover:text-blue-600 transition">Events &amp; Holidays</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Edit Event</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Event / Holiday</h1>
                    <p class="text-xs text-slate-500">Update event timings, audience settings, or description.</p>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('school.events') }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Events List</span>
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('school.events.update', $event->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Event / Holiday Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required value="{{ old('title', $event->title) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('title') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Type & Applies To -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Event Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" required class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="Event" {{ $event->type === 'Event' ? 'selected' : '' }}>Event</option>
                        <option value="Holiday" {{ $event->type === 'Holiday' ? 'selected' : '' }}>Institutional Holiday</option>
                        <option value="Exam" {{ $event->type === 'Exam' ? 'selected' : '' }}>Examination Session</option>
                        <option value="Sports" {{ $event->type === 'Sports' ? 'selected' : '' }}>Sports Meet &amp; Athletics</option>
                        <option value="Celebration" {{ $event->type === 'Celebration' ? 'selected' : '' }}>Cultural Celebration</option>
                        <option value="Meeting" {{ $event->type === 'Meeting' ? 'selected' : '' }}>Faculty / Board Meeting</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Applies To <span class="text-rose-500">*</span>
                    </label>
                    <select name="applies_to" required class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="All Staff & Students" {{ $event->applies_to === 'All Staff & Students' ? 'selected' : '' }}>All Staff &amp; Students</option>
                        <option value="Staff Only" {{ $event->applies_to === 'Staff Only' ? 'selected' : '' }}>Staff Only</option>
                        <option value="Students Only" {{ $event->applies_to === 'Students Only' ? 'selected' : '' }}>Students Only</option>
                        <option value="Parents Only" {{ $event->applies_to === 'Parents Only' ? 'selected' : '' }}>Parents Only</option>
                    </select>
                </div>
            </div>

            <!-- Start Date & End Date -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Start Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="start_date" required value="{{ old('start_date', $event->start_date ?: $event->event_date) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        End Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="end_date" required value="{{ old('end_date', $event->end_date ?: ($event->start_date ?: $event->event_date)) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Audience & Class Selection -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Audience Group
                    </label>
                    <select name="audience_type" id="eventAudienceSelect" onchange="toggleEventClass(this.value)" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="All" {{ $event->audience_type === 'All' ? 'selected' : '' }}>All Audience</option>
                        <option value="Staff" {{ $event->audience_type === 'Staff' ? 'selected' : '' }}>Faculty / Staff</option>
                        <option value="Students" {{ $event->audience_type === 'Students' ? 'selected' : '' }}>Students</option>
                        <option value="Class" {{ $event->audience_type === 'Class' ? 'selected' : '' }}>Specific Class</option>
                    </select>
                </div>

                <div id="eventClassWrap" class="{{ $event->audience_type === 'Class' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Select Specific Class
                    </label>
                    <select name="class_id" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $event->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Event Description / Notes
                </label>
                <textarea name="description" rows="5" class="w-full p-3.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed">{{ old('description', $event->description) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
                <a href="{{ route('school.events') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-save mr-1.5"></i> Update Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleEventClass(val) {
    const wrap = document.getElementById('eventClassWrap');
    if (val === 'Class') {
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }
}
</script>
@endsection


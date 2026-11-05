@extends('layouts.app')

@section('title', 'Edit Lesson Plan — ' . $plan->title)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('lesson_plans.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Edit Lesson Plan</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Update pedagogical objectives, methods, timing, or status</p>
        </div>
        <a href="{{ route('lesson-plans.plans') }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
            Back to Plans
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('lesson-plans.plans.update', $plan->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Plan Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Lesson Plan Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ $plan->title }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Teacher, Class, Section, Subject -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teacher / Faculty <span class="text-rose-500">*</span></label>
                    <select name="staff_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($staffMembers as $st)
                            <option value="{{ $st->id }}" {{ ($plan->staff_id == $st->id || $plan->teacher_id == $st->id) ? 'selected' : '' }}>{{ $st->first_name }} {{ $st->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $plan->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section</label>
                    <select name="section_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- All Sections --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ $plan->section_id == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ $plan->subject_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Week Dates, Periods & Status -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Week Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_start_date" required value="{{ $plan->week_start_date }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Week End Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_end_date" required value="{{ $plan->week_end_date }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Planned Periods <span class="text-rose-500">*</span></label>
                    <input type="number" name="planned_periods" required min="1" max="20" value="{{ $plan->planned_periods }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Plan Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white font-bold text-emerald-700">
                        @foreach(['Draft', 'Pending HOD', 'Pending Principal', 'Approved', 'Needs revision'] as $st)
                            <option value="{{ $st }}" {{ $plan->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Chapter Names & Learning Objectives -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Chapter / Unit Title</label>
                <input type="text" name="chapter_names" value="{{ $plan->chapter_names }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Learning Objectives & Outcomes</label>
                <textarea name="learning_objectives" rows="3" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">{{ $plan->learning_objectives }}</textarea>
            </div>

            <!-- Teaching Methods Checkboxes -->
            @php $currentMethods = $plan->teaching_methods ?? []; @endphp
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Teaching Methods</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach(['Lecture', 'Discussion', 'Group Activity', 'Problem Solving', 'Demonstration', 'Practical/Lab', 'Flipped Classroom'] as $m)
                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                        <input type="checkbox" name="teaching_methods[]" value="{{ $m }}" {{ in_array($m, $currentMethods) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>{{ $m }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Teaching Aids Checkboxes -->
            @php $currentAids = $plan->teaching_aids ?? []; @endphp
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Teaching Aids & Media</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach(['Whiteboard', 'Smart Board', 'PPT Slides', 'Models/Charts', 'Lab Equipment', 'Video/Audio', 'Worksheets'] as $a)
                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                        <input type="checkbox" name="teaching_aids[]" value="{{ $a }}" {{ in_array($a, $currentAids) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>{{ $a }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Homework & Resources -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Homework & Practice Assignments</label>
                    <textarea name="homework_planned" rows="2.5" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">{{ $plan->homework_planned }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Required Apparatus / Resources</label>
                    <textarea name="resources_required" rows="2.5" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">{{ $plan->resources_required }}</textarea>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('lesson-plans.plans') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


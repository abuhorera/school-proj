@extends('layouts.app')

@section('title', 'New Lesson Plan - Curriculum & Syllabus')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('lesson_plans.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-feather"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">New Lesson Plan</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Design weekly instructional pedagogy, topic breakdown, and learning objectives</p>
        </div>
        <a href="{{ route('lesson-plans.plans') }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
            Back to Plans
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('lesson-plans.plans.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Plan Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Lesson Plan Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Linear Equations in Two Variables & Graphical Solutions" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <!-- Teacher, Class, Section, Subject -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teacher / Faculty <span class="text-rose-500">*</span></label>
                    <select name="staff_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- Select Teacher --</option>
                        @foreach($staffMembers as $st)
                            <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->department }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section</label>
                    <select name="section_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- All Sections --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Week Dates & Periods -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Week Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_start_date" required value="{{ date('Y-m-d', strtotime('monday this week')) }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Week End Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="week_end_date" required value="{{ date('Y-m-d', strtotime('friday this week')) }}" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Planned Periods <span class="text-rose-500">*</span></label>
                    <input type="number" name="planned_periods" required min="1" max="20" value="5" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
            </div>

            <!-- Chapter Names & Learning Objectives -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Chapter / Unit Title</label>
                <input type="text" name="chapter_names" placeholder="e.g. Chapter 3: Pair of Linear Equations in Two Variables" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Learning Objectives & Outcomes</label>
                <textarea name="learning_objectives" rows="3" placeholder="What key concepts, skills, and competencies will students achieve by the end of this instructional week?..." class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <!-- Teaching Methods Checkboxes (Matching Live Demo) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Teaching Methods</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach(['Lecture', 'Discussion', 'Group Activity', 'Problem Solving', 'Demonstration', 'Practical/Lab', 'Flipped Classroom'] as $m)
                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                        <input type="checkbox" name="teaching_methods[]" value="{{ $m }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>{{ $m }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Teaching Aids Checkboxes (Matching Live Demo) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Teaching Aids & Media</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach(['Whiteboard', 'Smart Board', 'PPT Slides', 'Models/Charts', 'Lab Equipment', 'Video/Audio', 'Worksheets'] as $a)
                    <label class="flex items-center space-x-2 text-xs text-slate-700 cursor-pointer p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                        <input type="checkbox" name="teaching_aids[]" value="{{ $a }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>{{ $a }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Planned Topics (1 Per line) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Topics to Cover (1 per line) <span class="text-rose-500">*</span></label>
                <textarea name="adhoc_topics" rows="4" placeholder="Graphical representation of linear equations&#10;Algebraic substitution method&#10;Elimination by equating coefficients&#10;Cross-multiplication technique&#10;Word problems & real-world modeling" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 font-mono"></textarea>
                <p class="text-[10px] text-slate-400 mt-1">Each topic listed here will automatically generate an individual topic coverage tracker item.</p>
            </div>

            <!-- Homework & Resources -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Homework & Practice Assignments</label>
                    <textarea name="homework_planned" rows="2.5" placeholder="Assignments, textbook exercises, practice questions..." class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Required Apparatus / Resources</label>
                    <textarea name="resources_required" rows="2.5" placeholder="Textbooks, lab instruments, software, reference worksheets..." class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('lesson-plans.plans') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" name="action_type" value="save_draft" class="px-5 py-2.5 border border-emerald-600 text-emerald-700 hover:bg-emerald-50 rounded-xl text-xs font-bold transition">
                    Save as Draft
                </button>
                <button type="submit" name="action_type" value="submit_review" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Submit for Review</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


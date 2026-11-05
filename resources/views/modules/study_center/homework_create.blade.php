@extends('layouts.app')

@section('title', 'Create New Homework Assignment')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('study-center.index') }}" class="text-slate-700 hover:text-blue-600 font-medium">Study Center</a>
                <span>/</span>
                <a href="{{ route('study-center.homework') }}" class="text-slate-700 hover:text-blue-600 font-medium">Homework &amp; Assignments</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Create Assignment</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Create New Homework Assignment</h1>
                    <p class="text-xs text-slate-500">Publish task questions, submission deadlines, and link library study guides.</p>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('study-center.homework') }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-arrow-left text-slate-400"></i>
                <span>Back to Assignments</span>
            </a>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <form method="POST" action="{{ route('study-center.homework.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Homework Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Assignment Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Chapter 4 Practice Problems &amp; Critical Thinking Questions" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Class -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Section</label>
                    <select name="section_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                        <option value="">-- All Sections --</option>
                        <option value="1">Section A</option>
                        <option value="2">Section B</option>
                        <option value="3">Section C</option>
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Due Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Due Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                </div>

                <!-- Assigned By Teacher -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Assigned By (Teacher)</label>
                    <select name="staff_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                        @foreach($staffMembers as $st)
                            <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation->name ?? 'Faculty' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Description &amp; Instructions</label>
                <textarea name="description" rows="4" placeholder="Specify instructions for students, formatting guidelines, submission requirements, or textbook questions..." class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Link a Resource from Library -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Link a Resource from Library (Optional)</label>
                    <select name="study_material_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3 bg-white">
                        <option value="">-- No Linked Resource --</option>
                        @foreach($materials as $m)
                            <option value="{{ $m->id }}">{{ $m->title }} ({{ ucfirst($m->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Attachment file -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Worksheet Attachment (Optional)</label>
                    <input type="file" name="file" class="w-full text-xs border border-slate-200 rounded-xl py-2 px-3 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('study-center.homework') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-6 py-2.5 rounded-xl shadow-sm shadow-blue-500/20 transition">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Publish Homework</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection


@extends('layouts.app')

@section('title', 'Add New Examination')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs max-w-4xl mx-auto overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-lg">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold">Configure New Examination</h3>
                    <p class="text-xs text-slate-400">Setup academic term, dates, and participating classes</p>
                </div>
            </div>
            <a href="{{ route('exams.index') }}" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition">
                &larr; Back to Exams
            </a>
        </div>

        <form action="{{ route('exams.store') }}" method="POST" class="p-6 space-y-6 text-xs">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Exam Name -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Exam Title / Assessment Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Periodic Assessment 3 (PA-3) / Annual Exam 2027" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>

                <!-- Academic Session -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Academic Session <span class="text-rose-500">*</span></label>
                    <select name="academic_session_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                        @foreach($sessions as $sess)
                        <option value="{{ $sess->id }}" {{ $sess->is_current ? 'selected' : '' }}>{{ $sess->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Exam Type -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Exam Type / Classification</label>
                    <select name="type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                        <option value="Term Exam">Term Examination (Summative)</option>
                        <option value="Periodic Test">Periodic Unit Test (Formative)</option>
                        <option value="Pre-Board">Pre-Board Examination</option>
                        <option value="Final Exam">Final Annual Examination</option>
                        <option value="Remedial">Remedial Assessment</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+12 days')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Exam Description & Syllabus Notes</label>
                    <textarea name="description" rows="2" placeholder="e.g. Comprehensive 50% term evaluation according to CBSE guidelines..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs"></textarea>
                </div>
            </div>

            <!-- Applies To Classes -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Applicable Classes for Examination</h4>
                        <p class="text-[11px] text-slate-400">Select which grade standards will sit for this examination schedule</p>
                    </div>
                    <button type="button" onclick="toggleAllClasses(this)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold transition">
                        Toggle Select All
                    </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                    @foreach($classes as $c)
                    <label class="p-2.5 border border-slate-200 rounded-xl flex items-center space-x-2 bg-slate-50 hover:bg-purple-50 hover:border-purple-300 transition cursor-pointer">
                        <input type="checkbox" name="class_ids[]" value="{{ $c->id }}" checked class="class-checkbox rounded text-purple-600 focus:ring-purple-500">
                        <span class="font-bold text-slate-700 text-xs">{{ $c->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('exams.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition font-medium">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Save & Configure Timetable</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAllClasses(btn) {
    const checkboxes = document.querySelectorAll('.class-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endsection


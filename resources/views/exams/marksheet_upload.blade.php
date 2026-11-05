@extends('layouts.app')

@section('title', 'Upload External Marksheet')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Upload Form -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold">Upload External Marksheet PDF</h3>
                    <p class="text-xs text-slate-400">Upload scanned report cards, CBSE board certificates, or olympiad sheets</p>
                </div>
                <a href="{{ route('exams.marksheet.upload-list') }}" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition">
                    Manage Uploads &rarr;
                </a>
            </div>

            <form action="{{ route('exams.marksheet.upload.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Class</label>
                        <select name="class_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Section</label>
                        <select name="section_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                            @foreach($sections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Select Student <span class="text-rose-500">*</span></label>
                        <select name="student_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                            <option value="">-- Choose Candidate --</option>
                            @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->full_name }} ({{ $st->admission_number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Associated Exam</label>
                        <select name="exam_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                            <option value="">-- Optional Exam Link --</option>
                            @foreach($exams as $ex)
                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Marksheet Document Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. CBSE Class X Board Result / Olympiad Gold Certificate" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Marksheet Document File (PDF / JPG / PNG)</label>
                        <input type="file" name="marksheet_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                        <p class="text-[10px] text-slate-400 mt-1">Maximum allowed size: 10 MB. PDF format is recommended.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold shadow-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                        <span>Upload & Publish Marksheet</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right 1 Col: Instructions -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 space-y-3">
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Understanding External Marksheets</h4>
            <p class="text-xs text-slate-500 leading-relaxed">
                External marksheets allow schools to attach board certificates, olympiad achievements, state test scorecards, or scanned physical report cards to the student's lifelong academic record.
            </p>
            <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 text-xs text-purple-900">
                <i class="fa-solid fa-circle-info mr-1 text-purple-600"></i>
                Uploaded marksheets become instantly visible in the Student & Parent Mobile Portal once published.
            </div>
        </div>
    </div>
</div>
@endsection


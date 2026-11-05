@extends('layouts.app')

@section('title', 'Manage Online Exams - CBT Examination Suite')

@section('content')
<div class="space-y-6">
    @include('online_exams.partials.subnav')

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

    <!-- Header bar with Create Action -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-laptop-code"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Manage Online Exams — This Session</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Configure timed computer-based tests (CBT), quizzes, and practice papers with question bank integration</p>
        </div>
        <div>
            <button onclick="document.getElementById('createExamModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Create Online Exam</span>
            </button>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('online-exams.manage') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Paper Kind</label>
                <select name="type" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types (Exams & Quizzes)</option>
                    <option value="exam" {{ $kind == 'exam' ? 'selected' : '' }}>Exam (Formal)</option>
                    <option value="quiz" {{ $kind == 'quiz' ? 'selected' : '' }}>Quiz (Quick Assessment)</option>
                    <option value="practice" {{ $kind == 'practice' ? 'selected' : '' }}>Practice (Self-Paced)</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="Completed" {{ $status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Upcoming" {{ $status == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Draft" {{ $status == 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Class</label>
                <select name="class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter Papers</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Online Exams Master Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Registered Online Examination Papers ({{ $exams->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Kind</th>
                        <th class="py-3 px-4">Class & Section</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Start Time</th>
                        <th class="py-3 px-4">Duration</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($exams as $index => $ex)
                    @php
                        $statusColor = match($ex->status) {
                            'Active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'Completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'Upcoming' => 'bg-amber-100 text-amber-800 border-amber-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $ex->title }}</div>
                            <div class="text-[10px] text-slate-400 flex items-center space-x-2 mt-0.5">
                                <span>{{ $ex->questions_count }} Questions</span>
                                <span>•</span>
                                <span>{{ $ex->total_marks }} Marks</span>
                                @if($ex->negative_marking)
                                <span class="text-rose-500 font-bold">• (-{{ $ex->negative_mark_rate }} Neg)</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ex->kind == 'Exam' ? 'bg-purple-100 text-purple-800' : ($ex->kind == 'Quiz' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                {{ $ex->kind }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800">
                            {{ $ex->schoolClass->name ?? 'Class' }} ({{ $ex->section->name ?? 'A' }})
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-slate-700">{{ $ex->subject->name ?? 'General' }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                            {{ $ex->start_time ? $ex->start_time->format('d M, Y h:i A') : 'Flexible' }}
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $ex->duration }} mins
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusColor }}">
                                {{ $ex->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <!-- 1. Open Builder -->
                                <a href="{{ route('online-exams.builder', $ex->id) }}" class="p-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition" title="Open Paper Builder">
                                    <i class="fa-solid fa-pen-ruler text-xs"></i>
                                </a>
                                <!-- 2. Results & Submissions -->
                                <a href="{{ route('online-exams.results', $ex->id) }}" class="p-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition" title="View Results & Analytics">
                                    <i class="fa-solid fa-chart-simple text-xs"></i>
                                </a>
                                <!-- 3. Live CBT Test Simulator -->
                                <a href="{{ route('online-exams.take', $ex->id) }}" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="Launch CBT Simulator">
                                    <i class="fa-solid fa-play text-[9px]"></i>
                                    <span>Take</span>
                                </a>
                                <!-- 4. Delete -->
                                <form action="{{ route('online-exams.destroy', $ex->id) }}" method="POST" onsubmit="return confirm('Delete this online exam paper?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Paper">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-10 text-center text-slate-400">
                            <i class="fa-solid fa-laptop-code text-3xl text-slate-300 mb-2"></i>
                            <p class="text-sm font-semibold text-slate-600">No online exams found matching criteria.</p>
                            <button onclick="document.getElementById('createExamModal').classList.remove('hidden')" class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">
                                <i class="fa-solid fa-plus mr-1"></i> Create First Paper
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Create New Online Exam -->
<div id="createExamModal" class="{{ request()->get('new') ? '' : 'hidden' }} fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
            <h3 class="font-bold text-slate-800 text-sm">Create New Online Exam Paper</h3>
            <button onclick="document.getElementById('createExamModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('online-exams.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Paper Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Unit 1 - Algebra & Quadratic Equations" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Paper Kind <span class="text-rose-500">*</span></label>
                    <select name="kind" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="Exam">Exam (Formal High-Stakes)</option>
                        <option value="Quiz" selected>Quiz (Short Assessment)</option>
                        <option value="Practice">Practice (Self-Paced Learning)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Duration (Minutes) <span class="text-rose-500">*</span></label>
                    <input type="number" name="duration" value="30" min="5" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Start Time / Schedule</label>
                    <input type="datetime-local" name="start_time" value="{{ date('Y-m-d\TH:i') }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Passing Percentage (%)</label>
                    <input type="number" name="passing_percentage" value="40" min="1" max="100" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="negative_marking" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                    <span>Enable Negative Marking for Incorrect Choices</span>
                </label>
                <div class="text-[11px] text-slate-500">Deducts 0.25 to 0.50 marks per incorrect response.</div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Instructions for Students</label>
                <textarea name="instructions" rows="2" placeholder="e.g. Do not refresh or exit fullscreen during the examination..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500">Read all questions carefully. Choose the single best answer. No external reference material is permitted.</textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('createExamModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">Create & Open Builder</button>
            </div>
        </form>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'CBC Assessments & Rubric Scoring')

@section('content')
<div class="space-y-6">
    @include('cbc.partials.subnav')

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

    <!-- Header & Action -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">CBC Assessments & Rubric Scoring</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Conduct competency-based evaluations across 4 rubric achievement levels: EE, ME, AE, and BE</p>
        </div>
        <div>
            <button onclick="document.getElementById('createAssessmentModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Create New CBC Assessment</span>
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('cbc.assessments') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Class</label>
                <select name="class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Section</label>
                <select name="section_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Sections</option>
                    @foreach($sections as $s)
                    <option value="{{ $s->id }}" {{ $sectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Subject</label>
                <select name="subject_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter Assessments</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Assessments Grid & Rubric Matrix Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left: Assessment Selector List -->
        <div class="lg:col-span-1 space-y-3">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center justify-between">
                    <span>Assessments List</span>
                    <span class="bg-purple-100 text-purple-700 font-bold px-2 py-0.5 rounded-full text-[10px]">{{ $assessments->count() }}</span>
                </h3>
                <div class="space-y-2 max-h-[600px] overflow-y-auto pr-1">
                    @forelse($assessments as $a)
                    <a href="{{ route('cbc.assessments', array_merge(request()->query(), ['assessment_id' => $a->id])) }}" 
                       class="block p-3 rounded-xl border transition {{ $selectedAssessmentId == $a->id ? 'border-purple-500 bg-purple-50/60 shadow-xs' : 'border-slate-200/70 bg-white hover:bg-slate-50' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $a->type == 'Summative' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $a->type ?? 'Formative' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $a->assessment_date }}</span>
                        </div>
                        <h4 class="font-bold text-xs text-slate-800 truncate">{{ $a->title }}</h4>
                        <div class="text-[11px] text-slate-500 flex items-center justify-between mt-1">
                            <span>{{ $a->schoolClass->name ?? 'Class' }} - {{ $a->subject->name ?? 'Subject' }}</span>
                            <span class="text-purple-600 font-bold text-[10px]">{{ $a->evaluations->groupBy('student_id')->count() }} evaluated</span>
                        </div>
                    </a>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs">
                        <i class="fa-solid fa-clipboard-list text-2xl text-slate-300 mb-1"></i>
                        <p>No assessments found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Rubric Level Reference Guide -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 text-xs space-y-2.5">
                <h4 class="font-bold text-slate-800 text-[11px] uppercase tracking-wider border-b border-slate-100 pb-2">Rubric Performance Scale</h4>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-5 rounded bg-emerald-600 text-white font-black text-[10px] flex items-center justify-center">EE</span>
                    <div>
                        <div class="font-bold text-slate-800 text-[11px]">Exceeding Expectations</div>
                        <div class="text-[10px] text-slate-400">Level 4 • Demonstrates independent mastery & originality</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-5 rounded bg-blue-600 text-white font-black text-[10px] flex items-center justify-center">ME</span>
                    <div>
                        <div class="font-bold text-slate-800 text-[11px]">Meeting Expectations</div>
                        <div class="text-[10px] text-slate-400">Level 3 • Consistently achieves targeted outcomes</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-5 rounded bg-amber-500 text-white font-black text-[10px] flex items-center justify-center">AE</span>
                    <div>
                        <div class="font-bold text-slate-800 text-[11px]">Approaching Expectations</div>
                        <div class="text-[10px] text-slate-400">Level 2 • Developing skills; requires occasional guidance</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-5 rounded bg-rose-600 text-white font-black text-[10px] flex items-center justify-center">BE</span>
                    <div>
                        <div class="font-bold text-slate-800 text-[11px]">Below Expectations</div>
                        <div class="text-[10px] text-slate-400">Level 1 • Struggles with concepts; needs active intervention</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Rubric Scoring Matrix Form -->
        <div class="lg:col-span-3 space-y-4">
            @if($selectedAssessment)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold bg-purple-100 text-purple-800 px-2 py-0.5 rounded uppercase">{{ $selectedAssessment->type }} Assessment</span>
                        <span class="text-[10px] text-slate-400 font-mono">{{ $selectedAssessment->assessment_date }}</span>
                    </div>
                    <h2 class="text-base font-black text-slate-800 mt-1">{{ $selectedAssessment->title }}</h2>
                    <p class="text-xs text-slate-500">
                        {{ $selectedAssessment->schoolClass->name ?? '' }} • {{ $selectedAssessment->section->name ?? '' }} • {{ $selectedAssessment->subject->name ?? '' }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-500">{{ $students->count() }} Students Enrolled</span>
                </div>
            </div>

            <!-- Rubric Matrix Form -->
            <form action="{{ route('cbc.assessments.evaluate') }}" method="POST">
                @csrf
                <input type="hidden" name="cbc_assessment_id" value="{{ $selectedAssessment->id }}">

                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                    <div class="p-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-xs">Student Outcome Evaluation Sheet</h3>
                        <div class="flex items-center space-x-2 text-[11px] text-slate-500">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> EE
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span> ME
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-500"></span> AE
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-rose-500"></span> BE
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100 max-h-[700px] overflow-y-auto">
                        @forelse($students as $st)
                        @php
                            $stEvals = $existingEvals->get($st->id) ?? collect();
                            $feedback = $stEvals->first() ? $stEvals->first()->teacher_feedback : '';
                        @endphp
                        <div class="p-4 hover:bg-slate-50/40 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($st->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-800">{{ $st->first_name }} {{ $st->last_name }}</h4>
                                        <span class="text-[10px] text-slate-400 font-mono">Adm: {{ $st->admission_number ?? 'ST-'.$st->id }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('cbc.reports.student', $st->id) }}" target="_blank" class="text-[11px] text-indigo-600 hover:text-indigo-800 font-bold inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-id-card"></i>
                                        <span>View CBC Profile</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Outcomes rubric selectors -->
                            <div class="space-y-2 bg-slate-50/70 p-3 rounded-xl border border-slate-100">
                                @forelse($outcomes as $outcome)
                                @php
                                    $evalRecord = $stEvals->firstWhere('cbc_outcome_id', $outcome->id);
                                    $curLevel = $evalRecord ? $evalRecord->rubric_level : 'ME';
                                @endphp
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 py-1.5 border-b border-slate-200/40 last:border-0">
                                    <div class="sm:max-w-md">
                                        <span class="text-[10px] font-mono font-bold bg-white px-1.5 py-0.5 rounded border border-slate-200 text-slate-600">{{ $outcome->code }}</span>
                                        <span class="text-xs text-slate-700 font-medium ml-1">{{ $outcome->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @foreach(['EE' => 'emerald', 'ME' => 'blue', 'AE' => 'amber', 'BE' => 'rose'] as $lvl => $clr)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="evaluations[{{ $st->id }}][{{ $outcome->id }}]" 
                                                   value="{{ $lvl }}" 
                                                   {{ $curLevel == $lvl ? 'checked' : '' }} 
                                                   class="peer sr-only">
                                            <span class="px-2.5 py-1 text-[10px] font-black rounded-lg border transition peer-checked:bg-{{ $clr }}-600 peer-checked:text-white peer-checked:border-{{ $clr }}-600 bg-white text-slate-600 border-slate-200 hover:bg-slate-100">
                                                {{ $lvl }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-2 text-slate-400 text-xs italic">
                                    No outcomes linked to this subject. Please define strands & outcomes first.
                                </div>
                                @endforelse
                            </div>

                            <!-- Teacher qualitative notes -->
                            <div class="mt-3">
                                <input type="text" 
                                       name="feedback[{{ $st->id }}]" 
                                       value="{{ $feedback }}" 
                                       placeholder="Teacher's qualitative remarks / personalized observations for {{ $st->first_name }}..." 
                                       class="w-full text-xs rounded-xl border border-slate-200 px-3 py-1.5 focus:ring-2 focus:ring-purple-500 focus:outline-hidden">
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-users text-3xl text-slate-300 mb-2"></i>
                            <p>No students found for this class and section.</p>
                        </div>
                        @endforelse
                    </div>

                    @if($students->count() > 0)
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Ensure all rubric levels are accurately checked before saving.</span>
                        <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Save Evaluations & Remarks</span>
                        </button>
                    </div>
                    @endif
                </div>
            </form>
            @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-12 text-center">
                <i class="fa-solid fa-clipboard-check text-3xl text-purple-400 mb-2"></i>
                <h3 class="font-bold text-slate-800 text-sm">Select or Create an Assessment</h3>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1 mb-4">Choose an assessment from the left panel to begin recording student rubric mastery scores, or create a new assessment.</p>
                <button onclick="document.getElementById('createAssessmentModal').classList.remove('hidden')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-xs">
                    <i class="fa-solid fa-plus mr-1"></i> Create Assessment
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Create New CBC Assessment -->
<div id="createAssessmentModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Create CBC Assessment</h3>
            <button onclick="document.getElementById('createAssessmentModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('cbc.assessments.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Assessment Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Term 1 Formative Rubric Evaluation" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="class_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Section</label>
                    <select name="section_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ $sectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Evaluation Type</label>
                    <select name="type" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
                        <option value="Formative">Formative (Continuous)</option>
                        <option value="Summative">Summative (End of Term)</option>
                        <option value="Authentic Task">Authentic Task / Project</option>
                        <option value="Portfolio">Portfolio Review</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Date</label>
                <input type="date" name="assessment_date" value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Brief Description / Rubric Instructions</label>
                <textarea name="description" rows="2" placeholder="Tasks, experiments, or projects evaluated..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('createAssessmentModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-xs">Create Assessment</button>
            </div>
        </form>
    </div>
</div>
@endsection


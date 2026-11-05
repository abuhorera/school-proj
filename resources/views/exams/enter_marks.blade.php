@extends('layouts.app')

@section('title', 'Enter Examination Marks')

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

    <!-- Criteria Selection Card (Exact match to live demo) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Select Evaluation Criteria</h3>
        <form method="GET" action="{{ route('exams.marks.entry') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <!-- Exam -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Examination <span class="text-rose-500">*</span></label>
                <select name="exam_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">-- Select Exam --</option>
                    @foreach($exams as $ex)
                    <option value="{{ $ex->id }}" {{ $examId == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Class / Standard <span class="text-rose-500">*</span></label>
                <select name="class_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Section</label>
                <select name="section_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">All Sections</option>
                    @foreach($sections as $sec)
                    <option value="{{ $sec->id }}" {{ $sectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                <select name="subject_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $sub)
                    <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code ?? 'SUB' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-xs flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span>Fetch Students</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Student Marks Matrix -->
    @if($examId && $classId && $subjectId)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-purple-50/40">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <h3 class="font-bold text-slate-800 text-sm">
                        Enter Student Scores
                    </h3>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Class: <span class="font-semibold text-slate-700">{{ $classes->find($classId)->name ?? 'Class' }}</span> • 
                    Subject: <span class="font-semibold text-purple-700">{{ $subjects->find($subjectId)->name ?? 'Subject' }}</span>
                </p>
            </div>

            <div class="flex items-center space-x-3 text-xs">
                @if($schedule)
                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg">
                    Max Marks: <span class="text-purple-600">{{ $schedule->max_marks }}</span>
                </span>
                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 font-bold rounded-lg">
                    Pass Marks: <span class="text-emerald-600">{{ $schedule->pass_marks }}</span>
                </span>
                @else
                <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-800 font-bold rounded-lg">
                    Default Max Marks: 100
                </span>
                @endif
                <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg">
                    {{ $students->count() }} Candidates
                </span>
            </div>
        </div>

        @if($students->count() == 0)
        <div class="p-12 text-center text-slate-400 text-xs">
            <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-300"></i>
            <p>No active enrolled students found for the selected Class and Section criteria.</p>
        </div>
        @else
        <form action="{{ route('exams.marks.save') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_schedule_id" value="{{ $schedule ? $schedule->id : 1 }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="p-3.5">Roll No</th>
                            <th class="p-3.5">Admission No</th>
                            <th class="p-3.5">Candidate / Student</th>
                            <th class="p-3.5">Max Marks</th>
                            <th class="p-3.5 w-32">Marks Obtained <span class="text-rose-500">*</span></th>
                            <th class="p-3.5 text-center">Absent?</th>
                            <th class="p-3.5">Grade</th>
                            <th class="p-3.5">Evaluation Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $idx => $st)
                        @php
                            $m = $existingMarks->get($st->id);
                            $score = $m ? $m->marks_obtained : '';
                            $isAb = $m ? $m->is_absent : false;
                            $grd = $m ? $m->grade : '—';
                            $rem = $m ? $m->remarks : '';
                        @endphp
                        <tr class="hover:bg-slate-50 transition {{ $isAb ? 'bg-rose-50/30' : '' }}">
                            <td class="p-3.5 font-bold text-slate-500">{{ $st->roll_number ?? ($idx + 1) }}</td>
                            <td class="p-3.5 font-mono text-[11px] text-slate-600">{{ $st->admission_number }}</td>
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 text-sm">{{ $st->full_name }}</div>
                                <div class="text-[10px] text-slate-400">Class: {{ $st->latestEnrollment->schoolClass->name ?? 'Standard' }} ({{ $st->latestEnrollment->section->name ?? 'A' }})</div>
                            </td>
                            <td class="p-3.5 font-bold text-slate-600">{{ $schedule ? $schedule->max_marks : 100 }}</td>
                            <td class="p-3.5">
                                <input type="number" step="0.5" min="0" max="{{ $schedule ? $schedule->max_marks : 100 }}" name="marks[{{ $st->id }}]" value="{{ $score }}" placeholder="Score" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs font-bold text-slate-800 marks-input" {{ $isAb ? 'disabled' : '' }}>
                            </td>
                            <td class="p-3.5 text-center">
                                <label class="inline-flex items-center space-x-1 cursor-pointer">
                                    <input type="checkbox" name="absent[{{ $st->id }}]" value="1" {{ $isAb ? 'checked' : '' }} onchange="toggleAbsent(this, {{ $st->id }})" class="rounded text-rose-600 focus:ring-rose-500">
                                    <span class="text-[10px] font-bold text-slate-500">AB</span>
                                </label>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $grd == 'AB' ? 'bg-rose-100 text-rose-800' : (in_array($grd, ['A1', 'A2', 'A+']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $grd }}
                                </span>
                            </td>
                            <td class="p-3.5">
                                <input type="text" name="remarks[{{ $st->id }}]" value="{{ $rem }}" placeholder="e.g. Good grasp of concepts" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <span class="text-xs text-slate-500">Verify scores against max marks limit before saving.</span>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save All Marks</span>
                </button>
            </div>
        </form>
        @endif
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center shadow-xs text-slate-400">
        <i class="fa-solid fa-arrow-up-from-bracket text-3xl mb-2 text-purple-300"></i>
        <h4 class="font-bold text-slate-700 text-sm">Please Select Examination, Class, and Subject Above</h4>
        <p class="text-xs mt-1 text-slate-400">The student evaluation sheet will automatically populate with active candidates.</p>
    </div>
    @endif
</div>

<script>
function toggleAbsent(checkbox, studentId) {
    const input = document.querySelector(`input[name="marks[${studentId}]"]`);
    if (checkbox.checked) {
        input.disabled = true;
        input.value = "0";
    } else {
        input.disabled = false;
    }
}
</script>
@endsection


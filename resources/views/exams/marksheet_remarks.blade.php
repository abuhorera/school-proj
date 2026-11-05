@extends('layouts.app')

@section('title', 'Teacher Report Card Remarks')

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

    <!-- Criteria Selection Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Select Class & Examination for Observations</h3>
        <form method="GET" action="{{ route('exams.marksheet.remarks') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
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

            <!-- Search Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition shadow-xs flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-comment-dots text-xs"></i>
                    <span>Fetch Students</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Student Remarks Matrix -->
    @if($classId && $examId)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-purple-50/40">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Class Teacher's Observations & Holistic Comments</h3>
                <p class="text-[11px] text-slate-500">Remarks will be printed directly in Part 3 of the CBSE official report card</p>
            </div>
            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg text-xs">
                {{ $students->count() }} Students
            </span>
        </div>

        @if($students->count() == 0)
        <div class="p-12 text-center text-slate-400 text-xs">
            <p>No students found for selected criteria.</p>
        </div>
        @else
        <form action="{{ route('exams.marksheet.remarks.save') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $examId }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-1/4">Candidate / Student</th>
                            <th class="p-3.5 w-24">Attendance %</th>
                            <th class="p-3.5 w-36">Conduct Rating</th>
                            <th class="p-3.5">Report Card Teacher Remarks <span class="text-rose-500">*</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $st)
                        @php
                            $rem = $existingRemarks->get($st->id);
                            $note = $rem ? $rem->class_teacher_remarks : 'Shows good academic focus and discipline in class activities.';
                            $cond = $rem ? $rem->conduct : 'Exemplary';
                            $att = $rem ? $rem->attendance_percentage : 94.0;
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 text-sm">{{ $st->full_name }}</div>
                                <div class="text-[10px] text-slate-400">Roll No: {{ $st->roll_number ?? '—' }} • Adm: {{ $st->admission_number }}</div>
                            </td>
                            <td class="p-3.5">
                                <input type="number" step="0.5" name="attendance[{{ $st->id }}]" value="{{ $att }}" class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-emerald-700">
                            </td>
                            <td class="p-3.5">
                                <select name="conduct[{{ $st->id }}]" class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-purple-700">
                                    <option value="Exemplary" {{ $cond == 'Exemplary' ? 'selected' : '' }}>Exemplary</option>
                                    <option value="Very Good" {{ $cond == 'Very Good' ? 'selected' : '' }}>Very Good</option>
                                    <option value="Good" {{ $cond == 'Good' ? 'selected' : '' }}>Good</option>
                                    <option value="Satisfactory" {{ $cond == 'Satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                    <option value="Needs Focus" {{ $cond == 'Needs Focus' ? 'selected' : '' }}>Needs Focus</option>
                                </select>
                            </td>
                            <td class="p-3.5">
                                <input type="text" id="rem_input_{{ $st->id }}" name="remarks[{{ $st->id }}]" value="{{ $note }}" placeholder="Enter holistic observation note..." class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-purple-500 focus:bg-white transition">
                                <div class="mt-1 flex flex-wrap gap-1">
                                    <button type="button" onclick="setRemark({{ $st->id }}, 'Hardworking, inquisitive and highly disciplined.')" class="px-1.5 py-0.2 bg-slate-100 hover:bg-purple-100 text-[9px] text-slate-600 rounded">Hardworking</button>
                                    <button type="button" onclick="setRemark({{ $st->id }}, 'Shows strong analytical aptitude in science & mathematics.')" class="px-1.5 py-0.2 bg-slate-100 hover:bg-purple-100 text-[9px] text-slate-600 rounded">Analytical</button>
                                    <button type="button" onclick="setRemark({{ $st->id }}, 'Needs more consistent practice in written languages.')" class="px-1.5 py-0.2 bg-slate-100 hover:bg-purple-100 text-[9px] text-slate-600 rounded">Needs Practice</button>
                                    <button type="button" onclick="setRemark({{ $st->id }}, 'Creative thinker with natural leadership qualities.')" class="px-1.5 py-0.2 bg-slate-100 hover:bg-purple-100 text-[9px] text-slate-600 rounded">Leader</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end">
                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save All Remarks</span>
                </button>
            </div>
        </form>
        @endif
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center shadow-xs text-slate-400">
        <i class="fa-solid fa-comment-dots text-3xl mb-2 text-purple-300"></i>
        <h4 class="font-bold text-slate-700 text-sm">Select Class & Examination Above to Record Remarks</h4>
    </div>
    @endif
</div>

<script>
function setRemark(studentId, text) {
    document.getElementById('rem_input_' + studentId).value = text;
}
</script>
@endsection


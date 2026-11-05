@extends('layouts.app')

@section('title', 'Enter Cocurricular Grades')

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
        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Select Class & Section</h3>
        <form method="GET" action="{{ route('exams.cocurricular') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
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
                    <i class="fa-solid fa-palette text-xs"></i>
                    <span>Fetch Cocurricular Sheet</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Student Cocurricular Matrix -->
    @if($classId && $examId)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-purple-50/40">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Co-Scholastic & Cocurricular Evaluation Matrix</h3>
                <p class="text-[11px] text-slate-500">Graded on a 5-point scale (A, B, C, D, E) according to NEP 2020 holistic progress guidelines</p>
            </div>
            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg text-xs">
                {{ $students->count() }} Students
            </span>
        </div>

        @if($students->count() == 0)
        <div class="p-12 text-center text-slate-400 text-xs">
            <p>No students enrolled in this class criteria.</p>
        </div>
        @else
        <form action="{{ route('exams.cocurricular.save') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $examId }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="p-3.5">Candidate / Student</th>
                            @foreach($areas as $area)
                            <th class="p-3.5">{{ $area }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $st)
                        @php
                            $stGrades = $existingGrades->get($st->id) ? $existingGrades->get($st->id)->keyBy('area_name') : collect();
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 text-sm">{{ $st->full_name }}</div>
                                <div class="text-[10px] text-slate-400">Adm: {{ $st->admission_number }}</div>
                            </td>
                            @foreach($areas as $area)
                            @php
                                $currentGrade = $stGrades->get($area) ? $stGrades->get($area)->grade : 'A';
                            @endphp
                            <td class="p-3.5">
                                <select name="grades[{{ $st->id }}][{{ $area }}]" class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg font-bold text-purple-700 text-xs focus:outline-none focus:border-purple-500">
                                    <option value="A+" {{ $currentGrade == 'A+' ? 'selected' : '' }}>A+ (Exemplary)</option>
                                    <option value="A" {{ $currentGrade == 'A' ? 'selected' : '' }}>A (Outstanding)</option>
                                    <option value="B+" {{ $currentGrade == 'B+' ? 'selected' : '' }}>B+ (Very Good)</option>
                                    <option value="B" {{ $currentGrade == 'B' ? 'selected' : '' }}>B (Good)</option>
                                    <option value="C" {{ $currentGrade == 'C' ? 'selected' : '' }}>C (Satisfactory)</option>
                                    <option value="D" {{ $currentGrade == 'D' ? 'selected' : '' }}>D (Developing)</option>
                                </select>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end">
                <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Cocurricular Evaluation</span>
                </button>
            </div>
        </form>
        @endif
    </div>
    @else
    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center shadow-xs text-slate-400">
        <i class="fa-solid fa-palette text-3xl mb-2 text-purple-300"></i>
        <h4 class="font-bold text-slate-700 text-sm">Select Class and Examination to Evaluate Co-curricular Domains</h4>
    </div>
    @endif
</div>
@endsection


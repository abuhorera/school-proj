@extends('layouts.app')

@section('title', 'Offline Examinations Dashboard')

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

    <!-- Top KPI Cards Row (Exact Match to Live Demo) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Exams This Session -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Exams This Session</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalExams }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">{{ $activeExams }} active term exams</span>
                <span class="text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-full text-[10px]">SESSION 26-27</span>
            </div>
        </div>

        <!-- 2. Upcoming Exams -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Upcoming Exams</span>
                    <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $upcomingExams }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Scheduled on calendar</span>
                <span class="text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-full text-[10px]">SCHEDULED</span>
            </div>
        </div>

        <!-- 3. Marks Entry Progress -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Marks Entry</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $marksEntryPct }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">{{ $scoredSchedules }} of {{ $totalSchedules }} subjects scored</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full text-[10px]">EVALUATED</span>
            </div>
        </div>

        <!-- 4. Published Marksheets -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Published Marksheets</span>
                    <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $publishedMarksheets }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-certificate"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Report cards live</span>
                <span class="text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-full text-[10px]">ACCESSIBLE</span>
            </div>
        </div>
    </div>

    <!-- Quick Exam Setup Action Bar -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-slate-900 rounded-2xl p-6 text-white shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-purple-300 text-xs font-bold uppercase tracking-wider flex items-center space-x-1.5">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Quick Exam Workflow</span>
                </span>
                <h3 class="text-lg font-black mt-1">Conduct & Evaluate Term Examinations</h3>
                <p class="text-slate-300 text-xs mt-0.5">Follow the 4-step assessment lifecycle: Configure Exam &rarr; Setup Timetable &rarr; Enter Marks &rarr; Generate Marksheets.</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('exams.create') }}" class="px-3.5 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Create Exam</span>
                </a>
                <a href="{{ route('exams.schedule') }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-xs transition border border-white/20 flex items-center space-x-1.5">
                    <i class="fa-solid fa-calendar-days text-[10px]"></i>
                    <span>Setup Timetable</span>
                </a>
                <a href="{{ route('exams.marks.entry') }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs shadow-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    <span>Enter Marks</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Recent Examinations & Exam Components -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Recent / Active Exams Roster -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-check text-purple-600"></i>
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Offline Examinations Roster</h4>
                    </div>
                    <a href="{{ route('exams.index') }}" class="text-[11px] font-bold text-purple-600 hover:text-purple-800">Manage All Exams ({{ $totalExams }}) &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="p-3.5">Exam Name</th>
                                <th class="p-3.5">Session & Type</th>
                                <th class="p-3.5">Exam Dates</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($exams as $ex)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-800 text-sm">{{ $ex->name }}</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $ex->description ?? 'CBSE Assessment Scheme' }}</div>
                                </td>
                                <td class="p-3.5">
                                    <div class="font-semibold text-slate-700">{{ $ex->academicSession ? $ex->academicSession->name : '2026-2027' }}</div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 mt-1 inline-block">{{ $ex->type }}</span>
                                </td>
                                <td class="p-3.5 text-slate-600">
                                    @if($ex->start_date && $ex->end_date)
                                    <div>{{ \Carbon\Carbon::parse($ex->start_date)->format('d M, Y') }}</div>
                                    <div class="text-[10px] text-slate-400">to {{ \Carbon\Carbon::parse($ex->end_date)->format('d M, Y') }}</div>
                                    @else
                                    <span class="text-slate-400">Dates TBA</span>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ex->status == 'Active' ? 'bg-emerald-100 text-emerald-800' : ($ex->status == 'Upcoming' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-600') }}">
                                        {{ strtoupper($ex->status) }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <a href="{{ route('exams.schedule', ['exam_id' => $ex->id]) }}" class="px-2.5 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded text-[11px] font-semibold transition" title="Schedule Subjects">
                                            Schedule
                                        </a>
                                        <a href="{{ route('exams.marks.entry', ['exam_id' => $ex->id]) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded text-[11px] font-semibold transition" title="Enter Marks">
                                            Marks
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400">No examinations configured yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Marks Progress Bar & Schedule Overview -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Examination Marking & Scoring Velocity</h4>
                    <span class="text-xs font-bold text-emerald-600">{{ $marksEntryPct }}% Completed</span>
                </div>
                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $marksEntryPct }}%"></div>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ $scoredSchedules }} Subject Slots Graded</span>
                    <span>{{ $totalSchedules - $scoredSchedules }} Pending Evaluation</span>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Exam Types & Grading Scale Overview -->
        <div class="space-y-6">
            <!-- Exam Types Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Exam Evaluation Types</h4>
                    <a href="{{ route('exams.types') }}" class="text-[10px] text-purple-600 font-bold hover:underline">Manage &rarr;</a>
                </div>
                <div class="space-y-2.5">
                    @foreach($examTypes as $et)
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $et->name }}</span>
                            <span class="text-[10px] text-slate-400">Abbr: {{ $et->abbreviation }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700">
                            Weight: {{ $et->weightage }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- CBSE Grading Scale Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
                <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">CBSE 9-Point Grading Scale</h4>
                    <a href="{{ route('exams.grades') }}" class="text-[10px] text-purple-600 font-bold hover:underline">View All &rarr;</a>
                </div>
                <div class="space-y-1.5 text-xs">
                    @foreach($grades->take(6) as $grd)
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="font-bold text-slate-800 w-8">{{ $grd->name }}</span>
                        <span class="text-slate-500 text-[11px]">{{ $grd->min_percentage }}% - {{ $grd->max_percentage }}%</span>
                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $grd->description }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


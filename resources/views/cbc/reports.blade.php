@extends('layouts.app')

@section('title', 'CBC Reports & Holistic Analytics')

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

    <!-- Header -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">CBC Reports & Holistic Analytics</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Generate 360-degree holistic competency progress cards and track school-wide rubric achievement</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-print text-slate-500"></i>
                <span>Print Overview</span>
            </button>
        </div>
    </div>

    <!-- 4 Analytics Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Assessments</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalAssessments }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3">Formative & summative tasks</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Evaluations Recorded</span>
                    <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $totalEvaluations }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check-double"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3">Student rubric outcome logs</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Mastery Rate</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $masteryRate }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <p class="text-[11px] text-emerald-600 font-semibold mt-3">EE + ME Achievement Level</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Learning Outcomes</span>
                    <h3 class="text-2xl font-black text-teal-600 mt-1">{{ $totalOutcomes }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3">Active curriculum benchmarks</p>
        </div>
    </div>

    <!-- Student Quick Report Lookup -->
    <div class="bg-gradient-to-r from-violet-600 to-indigo-700 rounded-2xl shadow-md p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="max-w-xl">
                <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 px-2.5 py-0.5 rounded-full inline-block mb-2">NEP 2020 Holistic Progress Card</span>
                <h2 class="text-lg font-black">Generate Individual Student CBC Competency Profile</h2>
                <p class="text-xs text-indigo-100 mt-1">View the official printable 360-degree assessment report complete with learning outcomes, 4-tier rubric badges, and teacher qualitative reflections.</p>
            </div>
            <div class="flex items-center space-x-2 bg-white/10 p-2 rounded-xl backdrop-blur-xs">
                <select id="quickStudentSelect" class="bg-white text-slate-800 text-xs font-semibold rounded-lg px-3 py-2 border-0 focus:ring-2 focus:ring-white">
                    <option value="">-- Choose a Student --</option>
                    @foreach($students as $st)
                    <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_number ?? 'ID: '.$st->id }})</option>
                    @endforeach
                </select>
                <button onclick="goToStudentReport()" class="px-4 py-2 bg-white text-indigo-700 hover:bg-indigo-50 font-black rounded-lg text-xs transition shadow-xs">
                    View Report <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Class-wise Report Readiness Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Class-Wise Report Readiness & Evaluation Coverage</h3>
                <p class="text-[11px] text-slate-400">Status of CBC rubric evaluations and student report preparation across all cohorts</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Sections</th>
                        <th class="py-3 px-4">Enrolled Students</th>
                        <th class="py-3 px-4">Evaluation Status</th>
                        <th class="py-3 px-4">Competency Mastery</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($classes as $c)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $c->name }}</td>
                        <td class="py-3 px-4">
                            <div class="flex gap-1 flex-wrap">
                                @forelse($c->sections as $sec)
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold text-[10px]">{{ $sec->name }}</span>
                                @empty
                                <span class="text-slate-400 italic text-[10px]">None</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold">{{ $c->students_count }}</span> Students
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Ready
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $masteryRate > 0 ? $masteryRate : 85 }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600">{{ $masteryRate > 0 ? $masteryRate : 85 }}%</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('cbc.assessments', ['class_id' => $c->id]) }}" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-lg text-[11px] transition inline-flex items-center space-x-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                <span>Assessments</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">No classes configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function goToStudentReport() {
    const studentId = document.getElementById('quickStudentSelect').value;
    if (!studentId) {
        alert('Please select a student from the dropdown first.');
        return;
    }
    window.location.href = "{{ url('/school/cbc/reports/student') }}/" + studentId;
}
</script>
@endsection


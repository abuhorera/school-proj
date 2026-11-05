@extends('layouts.app')

@section('title', 'Online Examinations Dashboard')

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

    <!-- Top KPI Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Online Exams -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Online Papers</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalExams }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Exams & Quizzes</span>
                <span class="text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-full text-[10px]">ALL PAPERS</span>
            </div>
        </div>

        <!-- 2. Active / Live Papers -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Active Live Papers</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $activeExams }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Currently Open for CBT</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full text-[10px]">LIVE NOW</span>
            </div>
        </div>

        <!-- 3. Question Bank Size -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Question Bank Size</span>
                    <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $totalQuestions }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">MCQs, True/False & Descriptive</span>
                <span class="text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-full text-[10px]">ITEMS</span>
            </div>
        </div>

        <!-- 4. Average Score Rate -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Average Score Rate</span>
                    <h3 class="text-2xl font-black text-purple-600 mt-1">{{ $avgScoreRate }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Across {{ $totalAttempts }} student attempts</span>
                <span class="text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-full text-[10px]">PERFORMANCE</span>
            </div>
        </div>
    </div>

    <!-- Quick Guide Banner: How CBT Online Exams Work -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-800 rounded-2xl p-6 text-white shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="max-w-2xl">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white font-black text-[10px] uppercase tracking-wider">
                        Automated CBT Assessment System
                    </span>
                    <span class="text-xs text-blue-200">• MultiSchool CBT Engine</span>
                </div>
                <h2 class="text-xl font-black">Computer-Based Online Testing & Instant Evaluation</h2>
                <p class="text-xs text-blue-100 mt-1 leading-relaxed">
                    Create online exams, quizzes, or practice tests with negative marking, randomized questions, and time limits. Students take tests in a distraction-free CBT interface with instant automatic score generation.
                </p>
            </div>
            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('online-exams.question-bank.create') }}" class="px-4 py-2.5 bg-white text-blue-800 hover:bg-blue-50 font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add Question</span>
                </a>
                <a href="{{ route('online-exams.manage') }}?new=1" class="px-4 py-2.5 bg-blue-500/40 hover:bg-blue-500/60 border border-white/20 text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-file-circle-plus text-xs"></i>
                    <span>Create Paper</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Online Exams Performance Table (Exact match to Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Recent Examination Papers & Performance</h3>
                <p class="text-[11px] text-slate-400">Live submission records, average student performance, and difficulty band metrics</p>
            </div>
            <a href="{{ route('online-exams.manage') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center space-x-1">
                <span>View all papers</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Paper</th>
                        <th class="py-3 px-4">Kind</th>
                        <th class="py-3 px-4">Sat</th>
                        <th class="py-3 px-4">Average</th>
                        <th class="py-3 px-4">Hard band</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentExams as $ex)
                    @php
                        $satCount = $ex->attempts_count;
                        $avg = $satCount > 0 ? round($ex->attempts->avg('percentage'), 1) : 0;
                        $hardBand = $avg < 40 ? 'High Difficulty' : ($avg < 70 ? 'Moderate' : 'Balanced');
                        $bandColor = $avg < 40 ? 'bg-rose-100 text-rose-800' : ($avg < 70 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800');
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">{{ $ex->title }}</div>
                            <div class="text-[11px] text-slate-400">
                                {{ $ex->schoolClass->name ?? 'Class' }} • {{ $ex->subject->name ?? 'General' }} • {{ $ex->duration }} mins
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ex->kind == 'Exam' ? 'bg-purple-100 text-purple-800' : ($ex->kind == 'Quiz' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                {{ $ex->kind }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $satCount }} <span class="text-slate-400 font-normal">students</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-slate-800">{{ $avg }}%</span>
                                <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, $avg) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $bandColor }}">
                                {{ $hardBand }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('online-exams.builder', $ex->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Open Builder">
                                    <i class="fa-solid fa-pen-ruler text-xs"></i>
                                </a>
                                <a href="{{ route('online-exams.results', $ex->id) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="View Results">
                                    <i class="fa-solid fa-chart-simple text-xs"></i>
                                </a>
                                <a href="{{ route('online-exams.take', $ex->id) }}" class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="CBT Simulator">
                                    <i class="fa-solid fa-play text-[9px]"></i>
                                    <span>CBT Test</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">
                            <i class="fa-solid fa-laptop-code text-2xl text-slate-300 mb-2"></i>
                            <p>No online examination papers created yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


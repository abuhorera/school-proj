@extends('layouts.app')

@section('title', 'Competency-Based Curriculum Dashboard')

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

    <!-- Top KPI Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Core Competencies -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Core Competencies</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalCompetencies }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">21st-Century Framework</span>
                <span class="text-violet-700 font-bold bg-violet-50 px-2 py-0.5 rounded-full text-[10px]">NEP 2020</span>
            </div>
        </div>

        <!-- 2. Active Strands -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Curriculum Strands</span>
                    <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $totalStrands }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Subject Domain Areas</span>
                <span class="text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-full text-[10px]">ACTIVE</span>
            </div>
        </div>

        <!-- 3. Learning Outcomes -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Learning Outcomes</span>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $totalOutcomes }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Observable Benchmarks</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full text-[10px]">CRITERIA</span>
            </div>
        </div>

        <!-- 4. CBC Assessments -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">CBC Assessments</span>
                    <h3 class="text-2xl font-black text-purple-600 mt-1">{{ $totalAssessments }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-check-double"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs">
                <span class="text-slate-500">Formative & Summative</span>
                <span class="text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-full text-[10px]">EVALUATED</span>
            </div>
        </div>
    </div>

    <!-- 5-Step Setup Workflow (Exact match to Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Setup Workflow • Follow These Steps</h3>
                <p class="text-[11px] text-slate-400">Implement competency-based curriculum in a logical, guided sequence</p>
            </div>
            <form action="{{ route('cbc.competencies.seed') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                    <span>Seed Default Frameworks</span>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 text-xs">
            <!-- Step 1 -->
            <a href="{{ route('cbc.strands') }}" class="p-4 bg-slate-50 hover:bg-violet-50/50 rounded-xl border border-slate-200/80 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full bg-violet-600 text-white font-bold flex items-center justify-center text-[11px]">1</span>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-violet-600 transition"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-violet-700 transition">Strands & Outcomes</h4>
                <p class="text-[11px] text-slate-500 mt-1">Organize subject curricula into thematic strands and demonstrable learning outcomes.</p>
            </a>

            <!-- Step 2 -->
            <a href="{{ route('cbc.competencies') }}" class="p-4 bg-slate-50 hover:bg-violet-50/50 rounded-xl border border-slate-200/80 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full bg-violet-600 text-white font-bold flex items-center justify-center text-[11px]">2</span>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-violet-600 transition"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-violet-700 transition">Core Competencies</h4>
                <p class="text-[11px] text-slate-500 mt-1">Define NEP 2020 21st-century foundational skills, ethics, and digital competencies.</p>
            </a>

            <!-- Step 3 -->
            <a href="{{ route('cbc.assessments') }}" class="p-4 bg-slate-50 hover:bg-violet-50/50 rounded-xl border border-slate-200/80 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full bg-violet-600 text-white font-bold flex items-center justify-center text-[11px]">3</span>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-violet-600 transition"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-violet-700 transition">CBC Assessments</h4>
                <p class="text-[11px] text-slate-500 mt-1">Conduct formative tasks and score learners on a 4-tier rubric (EE, ME, AE, BE).</p>
            </a>

            <!-- Step 4 -->
            <a href="{{ route('cbc.reports') }}" class="p-4 bg-slate-50 hover:bg-violet-50/50 rounded-xl border border-slate-200/80 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full bg-violet-600 text-white font-bold flex items-center justify-center text-[11px]">4</span>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-violet-600 transition"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-violet-700 transition">CBC Reports</h4>
                <p class="text-[11px] text-slate-500 mt-1">Generate multi-dimensional competency profiles and holistic growth transcripts.</p>
            </a>

            <!-- Step 5 -->
            <a href="{{ route('cbc.pathways') }}" class="p-4 bg-slate-50 hover:bg-violet-50/50 rounded-xl border border-slate-200/80 transition group block">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full bg-violet-600 text-white font-bold flex items-center justify-center text-[11px]">5</span>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-violet-600 transition"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-violet-700 transition">Pathways & Tracks</h4>
                <p class="text-[11px] text-slate-500 mt-1">Configure Senior Secondary career pathways (STEM, Humanities, Arts & Sports).</p>
            </a>
        </div>
    </div>

    <!-- Comparative Table: Traditional Marks vs Competency-Based CBC (Exact match to Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Comparative Framework: Traditional Marks vs Competency-Based CBC</h4>
                <p class="text-[11px] text-slate-400">Key differences in pedagogy, assessment methodology, and learner progression</p>
            </div>
            <span class="text-[10px] font-bold text-violet-700 bg-violet-100 px-2 py-0.5 rounded-full">NEP 2020 PARADIGM</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-white text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5 w-1/4">Evaluation Aspect</th>
                        <th class="p-3.5 w-3/8 text-slate-600">Traditional Evaluation System</th>
                        <th class="p-3.5 w-3/8 text-violet-700 font-bold">Competency-Based Curriculum (CBC)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50">
                        <td class="p-3.5 font-bold text-slate-800">Assessment Focus</td>
                        <td class="p-3.5 text-slate-600">How many total marks scored out of 100 on written memory recall tests.</td>
                        <td class="p-3.5 font-medium text-violet-900 bg-violet-50/30">What authentic skills, problem-solving abilities, and demonstrable competencies the learner shows.</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3.5 font-bold text-slate-800">Performance Grading</td>
                        <td class="p-3.5 text-slate-600">Ranked against classmates (percentiles, bell curves, class rank 1, 2, 3).</td>
                        <td class="p-3.5 font-medium text-violet-900 bg-violet-50/30">Evaluated against criterion rubrics: EE (Exceeding), ME (Meeting), AE (Approaching), BE (Below).</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3.5 font-bold text-slate-800">Instructional Approach</td>
                        <td class="p-3.5 text-slate-600">Teacher-centric rote memorization and finishing textbook chapters.</td>
                        <td class="p-3.5 font-medium text-violet-900 bg-violet-50/30">Learner-centric experiential learning, inquiry-based investigations, and authentic peer collaboration.</td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="p-3.5 font-bold text-slate-800">Feedback Nature</td>
                        <td class="p-3.5 text-slate-600">Purely summative numerical scores at term-end with minimal diagnostic commentary.</td>
                        <td class="p-3.5 font-medium text-violet-900 bg-violet-50/30">Continuous formative feedback detailing specific demonstrable outcomes achieved and next growth targets.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Assessments & Rubrics')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Continuous Evaluation</div>
            <h1 class="text-2xl font-bold text-slate-800">Assessments & Formative Rubrics</h1>
            <p class="text-xs text-slate-500 mt-1">Formative assessments, oral evaluations, project portfolios, and subjective rubric scoring.</p>
        </div>
        <button onclick="alert('Create assessment')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>New Assessment</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Formative Rubric Assessments</h3>
            <p class="text-xs text-slate-500 mt-1">Assess students on communication, critical thinking, and laboratory skills.</p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">12 Active Rubrics</span>
                <button class="text-emerald-600 font-bold hover:underline">View Rubrics →</button>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-flask"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Practical & STEM Labs</h3>
            <p class="text-xs text-slate-500 mt-1">Record lab experiment scores and viva-voce evaluations.</p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Physics & Chem</span>
                <button class="text-blue-600 font-bold hover:underline">Grade Sheet →</button>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-palette"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">Co-Scholastic & Art Integration</h3>
            <p class="text-xs text-slate-500 mt-1">Visual arts, music, dance, and physical fitness grading.</p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Grades A, B, C</span>
                <button class="text-purple-600 font-bold hover:underline">Enter Grades →</button>
            </div>
        </div>
    </div>
</div>
@endsection


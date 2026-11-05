@extends('layouts.app')

@section('title', 'Holistic Progress Card & CBC Framework')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-violet-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">Holistic Progress Card (HPC) & CBC Framework</h1>
            </div>
            <p class="text-xs text-slate-500">National Education Policy (NEP 2020) aligned competency strands, learning domains, and progress cards</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- 4 Learning Domains -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs mb-2">
                <i class="fa-solid fa-brain"></i>
            </div>
            <h4 class="font-bold text-xs text-slate-900">Cognitive & Inquiry</h4>
            <span class="text-[10px] text-slate-400">Critical thinking, logic & scientific curiosity</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs mb-2">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>
            <h4 class="font-bold text-xs text-slate-900">Physical & Motor Skills</h4>
            <span class="text-[10px] text-slate-400">Athletics, coordination & health habits</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs mb-2">
                <i class="fa-solid fa-palette"></i>
            </div>
            <h4 class="font-bold text-xs text-slate-900">Aesthetic & Cultural</h4>
            <span class="text-[10px] text-slate-400">Art, music, creative expression & drama</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-pink-100 text-pink-700 flex items-center justify-center font-bold text-xs mb-2">
                <i class="fa-solid fa-people-arrows"></i>
            </div>
            <h4 class="font-bold text-xs text-slate-900">Socio-Emotional Domain</h4>
            <span class="text-[10px] text-slate-400">Empathy, peer collaboration & leadership</span>
        </div>
    </div>

    <!-- Competency Matrix Table -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-cube text-violet-600"></i>
                <span>Active Core Competency Strands (CBSE & NEP 2020)</span>
            </h2>
            <span class="text-xs bg-violet-50 text-violet-700 font-bold px-2.5 py-0.5 rounded-full">Standard Matrix</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Strand Code</th>
                        <th class="py-2.5 px-3">Competency Area</th>
                        <th class="py-2.5 px-3">Mapped Domain</th>
                        <th class="py-2.5 px-3">Assessment Rubric</th>
                        <th class="py-2.5 px-3">Target Grades</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2.5 px-3 font-mono font-bold text-violet-700">CBC-01</td>
                        <td class="py-2.5 px-3 font-bold text-slate-900">Scientific Thinking & Evidence Derivation</td>
                        <td class="py-2.5 px-3 text-slate-600">Cognitive & Inquiry</td>
                        <td class="py-2.5 px-3 text-slate-500">Observation, hypothesis, and lab documentation</td>
                        <td class="py-2.5 px-3"><span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px]">Class 6-10</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2.5 px-3 font-mono font-bold text-violet-700">CBC-02</td>
                        <td class="py-2.5 px-3 font-bold text-slate-900">Effective Multilingual Communication</td>
                        <td class="py-2.5 px-3 text-slate-600">Linguistic Domain</td>
                        <td class="py-2.5 px-3 text-slate-500">Speech clarity, active listening, and essay construction</td>
                        <td class="py-2.5 px-3"><span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px]">Class 1-10</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2.5 px-3 font-mono font-bold text-violet-700">CBC-03</td>
                        <td class="py-2.5 px-3 font-bold text-slate-900">Digital Fluency & Computational Logic</td>
                        <td class="py-2.5 px-3 text-slate-600">Technology Domain</td>
                        <td class="py-2.5 px-3 text-slate-500">Block coding, algorithm structuring, and typing precision</td>
                        <td class="py-2.5 px-3"><span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px]">Class 4-10</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2.5 px-3 font-mono font-bold text-violet-700">CBC-04</td>
                        <td class="py-2.5 px-3 font-bold text-slate-900">Collaborative Teamwork & Civic Empathy</td>
                        <td class="py-2.5 px-3 text-slate-600">Socio-Emotional Domain</td>
                        <td class="py-2.5 px-3 text-slate-500">Peer mentoring, group project responsibility, and conflict resolution</td>
                        <td class="py-2.5 px-3"><span class="bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded text-[10px]">All Grades</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection


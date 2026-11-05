@extends('layouts.app')

@section('title', 'Enter Examination Marks')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Offline Examinations</div>
            <h1 class="text-2xl font-bold text-slate-800">Enter & Verify Student Marks</h1>
            <p class="text-xs text-slate-500 mt-1">Bulk entry of theory, practical and internal assessment marks with auto-grade calculations.</p>
        </div>
        <button onclick="alert('Marks successfully saved!')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-4 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Save All Marks</span>
        </button>
    </div>

    <!-- Filter selectors -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap items-center gap-4 text-xs">
        <div>
            <label class="block font-bold text-slate-700 mb-1">Select Exam</label>
            <select class="border border-slate-300 rounded-lg p-2 bg-slate-50">
                <option selected>Mid Term Examination 2026-27</option>
                <option>Unit Test 1</option>
            </select>
        </div>
        <div>
            <label class="block font-bold text-slate-700 mb-1">Class</label>
            <select class="border border-slate-300 rounded-lg p-2 bg-slate-50">
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $c->name == 'Class 10' ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-bold text-slate-700 mb-1">Subject</label>
            <select class="border border-slate-300 rounded-lg p-2 bg-slate-50">
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} (Max: 100)</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Marks Entry Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Adm #</th>
                    <th class="py-3 px-4">Roll</th>
                    <th class="py-3 px-4">Student Name</th>
                    <th class="py-3 px-4">Theory Marks (Max: 80)</th>
                    <th class="py-3 px-4">Practical / IA (Max: 20)</th>
                    <th class="py-3 px-4">Total Marks (100)</th>
                    <th class="py-3 px-4">Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($students as $idx => $st)
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-mono text-slate-600">{{ $st->admission_number }}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">{{ $idx + 1 }}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">{{ $st->full_name }}</td>
                    <td class="py-3 px-4">
                        <input type="number" value="{{ 68 + ($idx % 10) }}" max="80" class="w-20 border border-slate-300 rounded px-2 py-1 bg-slate-50 font-semibold text-center">
                    </td>
                    <td class="py-3 px-4">
                        <input type="number" value="18" max="20" class="w-20 border border-slate-300 rounded px-2 py-1 bg-slate-50 font-semibold text-center">
                    </td>
                    <td class="py-3 px-4 font-bold text-slate-800">
                        {{ (68 + ($idx % 10)) + 18 }} / 100
                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">A1</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


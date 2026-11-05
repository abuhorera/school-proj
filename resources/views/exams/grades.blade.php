@extends('layouts.app')

@section('title', 'Manage Grading Scales')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Master Grades Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">CBSE 9-Point Grading Scale Master</h3>
                    <p class="text-[11px] text-slate-400">Standardized benchmarks for automatic grade calculation from percentages</p>
                </div>
                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">{{ $grades->count() }} TIERS</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="p-3.5">#</th>
                            <th class="p-3.5">Grade Name</th>
                            <th class="p-3.5">Percentage Range</th>
                            <th class="p-3.5">Grade Point</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($grades as $idx => $g)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $g->name }}
                                </span>
                            </td>
                            <td class="p-3.5 font-bold text-slate-700">
                                {{ $g->min_percentage }}% - {{ $g->max_percentage }}%
                            </td>
                            <td class="p-3.5 text-slate-800 font-semibold">
                                {{ $g->grade_point }}
                            </td>
                            <td class="p-3.5 text-slate-500">
                                {{ $g->description ?? '—' }}
                            </td>
                            <td class="p-3.5 text-right">
                                <form action="{{ route('exams.grades.delete', $g->id) }}" method="POST" onsubmit="return confirm('Delete grade tier \'{{ addslashes($g->name) }}\'?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Delete Grade">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">No grading scales defined.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 Col: Add New Grade Tier Form -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5">
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-3">Add Grading Tier</h4>
            <form action="{{ route('exams.grades.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Grade Symbol <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. A1 / A+ / O" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Min % <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="min_percentage" required placeholder="91" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Max % <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="max_percentage" required placeholder="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Grade Point (GPA)</label>
                    <input type="number" step="0.1" name="grade_point" placeholder="10.0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Performance Description</label>
                    <input type="text" name="description" placeholder="e.g. Outstanding / Exemplary" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition text-xs">
                </div>
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition shadow-xs">
                    Save Grade Scale
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


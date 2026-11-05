@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-cyan-600"></i>
                <span>Student Demographic Categories Master</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Reservation and demographic quotas (General, OBC, SC, ST, EWS / RTE) for statutory state reporting</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('modal-add-category').classList.remove('hidden')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Category</span>
            </button>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Demographic Categories ({{ $categories->count() }})</span>
            <span class="text-[11px] text-slate-400">Used for CBSE, state board, and fee concession quotas</span>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">#</th>
                        <th class="py-3 px-4">Category Name</th>
                        <th class="py-3 px-4">Quota Code</th>
                        <th class="py-3 px-4 text-center">Allocated Students</th>
                        <th class="py-3 px-4">Statutory Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $idx => $cat)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ $cat->name }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-cyan-700">
                            {{ $cat->code ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                {{ $cat->students_count }} Students
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                            {{ $cat->description ?? 'Standard demographic reservation head' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            No student categories configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Category -->
<div id="modal-add-category" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-cyan-600"></i>
                <span>Add Demographic Category</span>
            </h3>
            <button onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('students.categories.store') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. OBC (Non-Creamy Layer), EWS" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-cyan-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Quota Short Code</label>
                <input type="text" name="code" placeholder="e.g. OBC, EWS, SC" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-cyan-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Statutory Guidelines / Notes</label>
                <textarea name="description" rows="2" placeholder="e.g. RTE 25% free admission allocation..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-cyan-500"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Save Category</button>
            </div>
        </form>
    </div>
</div>
@endsection


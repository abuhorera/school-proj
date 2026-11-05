@extends('layouts.app')

@section('title', 'Subjects Master - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('academics.subjects') }}" class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Filter:</span>
            
            <select name="group" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">-- All Subject Groups --</option>
                @foreach($groups as $grp)
                    <option value="{{ $grp }}" {{ $groupFilter == $grp ? 'selected' : '' }}>{{ $grp }}</option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">-- All Types (Theory/Lab) --</option>
                @foreach($types as $tp)
                    <option value="{{ $tp }}" {{ $typeFilter == $tp ? 'selected' : '' }}>{{ $tp }}</option>
                @endforeach
            </select>

            @if($groupFilter || $typeFilter)
                <a href="{{ route('academics.subjects') }}" class="text-xs text-rose-600 hover:text-rose-800 font-medium px-2 py-1">
                    Clear
                </a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addSubjectModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Subject</span>
            </button>
            <a href="{{ route('academics.assign-subjects') }}" class="px-3 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-link text-[10px]"></i>
                <span>Assign to Classes</span>
            </a>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-book-bookmark text-sky-600"></i>
                <span class="text-xs font-bold text-slate-800">Master Subjects Catalog ({{ $subjects->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">NEP 2020 Multi-disciplinary curriculum</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">ID</th>
                        <th class="p-3.5">Subject Name</th>
                        <th class="p-3.5">Subject Code</th>
                        <th class="p-3.5">Curriculum Group</th>
                        <th class="p-3.5 text-center">Type</th>
                        <th class="p-3.5 text-center">Nature</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjects as $sub)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                #{{ $sub->id }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-[10px]">
                                        <i class="fa-solid fa-book-open"></i>
                                    </span>
                                    <span class="text-sm">{{ $sub->name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <span class="font-mono font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded text-[11px] border border-slate-200">
                                    {{ $sub->code }}
                                </span>
                            </td>
                            <td class="p-3.5 font-medium text-slate-700">
                                <span class="inline-flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    <span>{{ $sub->group ?? 'General Curriculum' }}</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($sub->type == 'Practical')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                        Practical / Lab
                                    </span>
                                @elseif($sub->type == 'Both')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Theory + Lab
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                        Theory
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                @if($sub->is_elective)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                        Elective Choice
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600">
                                        Core Compulsory
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('academics.assign-subjects') }}" title="Assign to Classes" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition">
                                        <i class="fa-solid fa-link"></i>
                                    </a>
                                    <form method="POST" action="{{ route('academics.subjects.delete', $sub->id) }}" onsubmit="return confirm('Delete subject {{ $sub->name }}?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Subject" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400">
                                No subjects registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Subject -->
<div id="addSubjectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-book-bookmark text-sky-400"></i>
                <span>Add Master Subject</span>
            </h4>
            <button onclick="document.getElementById('addSubjectModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.subjects.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Social Science, Sanskrit, Computer Applications" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" required placeholder="e.g. SST-087, PHY-042" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Evaluation Type <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="Theory">Theory</option>
                        <option value="Practical">Practical / Lab</option>
                        <option value="Both">Both (Theory + Lab)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Curriculum Group</label>
                <select name="group" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="Language">Language</option>
                    <option value="Science & Mathematics">Science & Mathematics</option>
                    <option value="Social Studies">Social Studies</option>
                    <option value="Commerce & Humanities">Commerce & Humanities</option>
                    <option value="Vocational & Arts">Vocational & Arts</option>
                    <option value="Sports & Physical Ed">Sports & Physical Ed</option>
                </select>
            </div>

            <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <input type="checkbox" id="is_elective" name="is_elective" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_elective" class="text-xs text-slate-700 font-medium">This is an Elective / Optional Subject</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addSubjectModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Save Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

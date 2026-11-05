@extends('layouts.app')

@section('title', 'Classes Management - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-800">Classes Master Catalog</h3>
            <p class="text-xs text-slate-500">Configure standard grades from Pre-Primary (Nursery, KG) through Senior Secondary (Class XII)</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addClassModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Class</span>
            </button>
            <a href="{{ route('academics.assign-subjects') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-link text-indigo-600"></i>
                <span>Assign Subjects</span>
            </a>
            <a href="{{ route('academics.sections') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-users-rectangle text-emerald-600"></i>
                <span>Manage Sections</span>
            </a>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-chalkboard text-indigo-600"></i>
                <span class="text-xs font-bold text-slate-800">All Registered Classes ({{ $classes->count() }})</span>
            </div>
            <div class="text-xs text-slate-400">
                Sorted by standard academic level
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">ID</th>
                        <th class="p-3.5">Class Name</th>
                        <th class="p-3.5">Sections Configured</th>
                        <th class="p-3.5">Class Coordinator</th>
                        <th class="p-3.5 text-center">Students Enrolled</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classes as $cls)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                #{{ $cls->id }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                        {{ $cls->numeric_level ?? substr($cls->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="text-sm">{{ $cls->name }}</span>
                                        <div class="text-[10px] text-slate-400 font-normal">Level {{ $cls->numeric_level ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @forelse($cls->sections as $sec)
                                        <a href="{{ route('academics.sections', ['class_id' => $cls->id]) }}" class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                            Sec {{ $sec->name }}
                                        </a>
                                    @empty
                                        <span class="text-slate-400 italic">No sections</span>
                                    @endforelse
                                    <a href="{{ route('academics.sections', ['class_id' => $cls->id]) }}" title="Add Section" class="px-1.5 py-0.5 text-[10px] rounded bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold">
                                        + Add
                                    </a>
                                </div>
                            </td>
                            <td class="p-3.5">
                                @if($cls->coordinator)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($cls->coordinator->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $cls->coordinator->first_name }} {{ $cls->coordinator->last_name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $cls->coordinator->designation ?? 'Teacher' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Not Assigned</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="font-bold text-slate-800 bg-slate-100 px-3 py-1 rounded-full text-xs">
                                    {{ $cls->students_count }} Enrolled
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('academics.sections', ['class_id' => $cls->id]) }}" title="Manage Sections" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition">
                                        <i class="fa-solid fa-users-rectangle"></i>
                                    </a>
                                    <a href="{{ route('academics.timetable', ['class_id' => $cls->id]) }}" title="View Timetable" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </a>
                                    <form method="POST" action="{{ route('academics.classes.delete', $cls->id) }}" onsubmit="return confirm('Are you sure you want to delete {{ $cls->name }} and all its sections?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Class" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">
                                No classes configured.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Class -->
<div id="addClassModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-chalkboard text-indigo-400"></i>
                <span>Add New Class</span>
            </h4>
            <button onclick="document.getElementById('addClassModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.classes.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Class / Grade Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Class 11 Science, Nursery, KG" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Numeric Level</label>
                    <input type="number" name="numeric_level" placeholder="e.g. 1, 2, 11" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Default Section</label>
                    <input type="text" disabled value="Section A (Auto Created)" class="w-full text-xs rounded-lg border border-slate-200 bg-slate-100 p-2.5 text-slate-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Class Coordinator (Staff)</label>
                <select name="coordinator_id" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Assign Later --</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation ?? 'Faculty' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addClassModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Create Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

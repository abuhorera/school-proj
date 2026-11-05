@extends('layouts.app')

@section('title', 'Sections Management - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <form method="GET" action="{{ route('academics.sections') }}" class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Filter by Class:</span>
            <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border border-slate-300 py-1.5 px-3 font-medium text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">-- All Classes ({{ $classes->count() }}) --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            @if($classId)
                <a href="{{ route('academics.sections') }}" class="text-xs text-rose-600 hover:text-rose-800 font-medium px-2 py-1">
                    Clear Filter
                </a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('addSectionModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Section</span>
            </button>
            <a href="{{ route('academics.assign-class-teacher') }}" class="px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition flex items-center space-x-1.5">
                <i class="fa-solid fa-chalkboard-user text-emerald-600"></i>
                <span>Assign In-Charges</span>
            </a>
        </div>
    </div>

    <!-- Sections Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-users-rectangle text-emerald-600"></i>
                <span class="text-xs font-bold text-slate-800">Sections Directory ({{ $sections->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Classrooms and student capacity</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">ID</th>
                        <th class="p-3.5">Section Name</th>
                        <th class="p-3.5">Associated Class</th>
                        <th class="p-3.5">Section In-Charge (Teacher)</th>
                        <th class="p-3.5 text-center">Max Capacity</th>
                        <th class="p-3.5 text-center">Students Enrolled</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sections as $sec)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                #{{ $sec->id }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Section {{ $sec->name }}
                                </span>
                            </td>
                            <td class="p-3.5 font-semibold text-slate-800">
                                {{ $sec->schoolClass->name ?? 'Class #' . $sec->class_id }}
                            </td>
                            <td class="p-3.5">
                                @if($sec->inChargeTeacher)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($sec->inChargeTeacher->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800">{{ $sec->inChargeTeacher->first_name }} {{ $sec->inChargeTeacher->last_name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $sec->inChargeTeacher->designation ?? 'Teacher' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Not Assigned</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center font-medium text-slate-600">
                                {{ $sec->capacity ?? 40 }} seats
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full text-[11px]">
                                    {{ $sec->students_count }} enrolled
                                </span>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('academics.timetable', ['class_id' => $sec->class_id, 'section_id' => $sec->id]) }}" title="View Timetable" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </a>
                                    <form method="POST" action="{{ route('academics.sections.delete', $sec->id) }}" onsubmit="return confirm('Delete Section {{ $sec->name }}?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Section" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400">
                                No sections found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Section -->
<div id="addSectionModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-users-rectangle text-emerald-400"></i>
                <span>Add Section to Class</span>
            </h4>
            <button onclick="document.getElementById('addSectionModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.sections.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select Class <span class="text-rose-500">*</span></label>
                <select name="class_id" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Choose Class --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Section Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. B, C, Rose, Lotus" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Max Capacity</label>
                    <input type="number" name="capacity" value="40" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Section In-Charge Teacher</label>
                <select name="in_charge_teacher_id" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Assign Later --</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation ?? 'Faculty' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addSectionModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Add Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

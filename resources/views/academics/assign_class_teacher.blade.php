@extends('layouts.app')

@section('title', 'Assign Subject Teacher - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-xl p-5 text-white shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Teacher Allocation</span>
            </div>
            <h3 class="text-base font-bold text-white leading-tight">Assign teachers to subjects per section</h3>
            <p class="text-xs text-indigo-200 mt-1 max-w-2xl">
                Map designated subject teachers to every section so attendance, grading and period schedules are automatically routed to their faculty portals.
            </p>
        </div>
        <div>
            <span class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs font-medium border border-white/10">
                {{ $classes->count() }} Classes • {{ $staff->count() }} Faculty Members
            </span>
        </div>
    </div>

    <!-- Class Cards with Sections Grid -->
    <div class="space-y-6">
        @foreach($classes as $cls)
            @php
                // Distinct subjects assigned to this class
                $classSubjects = $cls->classSubjects->unique('subject_id');
                $sections = $cls->sections;
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <form method="POST" action="{{ route('academics.assign-class-teacher.store') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $cls->id }}">

                    <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ $cls->numeric_level ?? substr($cls->name, 0, 2) }}
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $cls->name }}</h4>
                                <div class="text-[11px] text-slate-500">
                                    {{ $classSubjects->count() }} {{ Str::plural('Subject', $classSubjects->count()) }} • {{ $sections->count() }} {{ Str::plural('Section', $sections->count()) }}
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                            <span>Save {{ $cls->name }}</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-slate-100/70 text-slate-700 font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="p-3.5 w-64">Subject</th>
                                    @foreach($sections as $sec)
                                        <th class="p-3.5">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-800">
                                                Section {{ $sec->name }}
                                            </span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($classSubjects as $cs)
                                    @php $subj = $cs->subject; @endphp
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="p-3.5 font-bold text-slate-800">
                                            <div class="flex items-center space-x-2">
                                                <i class="fa-solid fa-book text-slate-400 text-xs"></i>
                                                <span>{{ $subj->name ?? 'Subject' }}</span>
                                                <span class="text-[10px] font-mono text-slate-400">({{ $subj->code ?? '' }})</span>
                                            </div>
                                        </td>
                                        @foreach($sections as $sec)
                                            @php
                                                // Find if a teacher is already assigned for this class, section, and subject
                                                $assignedRecord = $cls->classSubjects
                                                    ->where('section_id', $sec->id)
                                                    ->where('subject_id', $subj->id ?? 0)
                                                    ->first();
                                                $assignedTeacherId = $assignedRecord ? $assignedRecord->teacher_id : null;
                                            @endphp
                                            <td class="p-3.5">
                                                <select name="assignments[{{ $sec->id }}][{{ $subj->id ?? 0 }}]" class="w-full text-xs rounded-lg border border-slate-300 py-1.5 px-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                                                    <option value="">-- Select Teacher --</option>
                                                    @foreach($staff as $st)
                                                        <option value="{{ $st->id }}" {{ $assignedTeacherId == $st->id ? 'selected' : '' }}>
                                                            {{ $st->first_name }} {{ $st->last_name }} ({{ $st->designation ?? 'Faculty' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $sections->count() + 1 }}" class="p-6 text-center text-slate-400">
                                            No subjects assigned to this class yet. <a href="{{ route('academics.assign-subjects') }}" class="text-indigo-600 font-semibold underline">Assign Subjects &rarr;</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection


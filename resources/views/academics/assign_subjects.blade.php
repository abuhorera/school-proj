@extends('layouts.app')

@section('title', 'Assign Subjects to Classes - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-xl p-5 text-white shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="fa-solid fa-link"></i>
                <span>Curriculum Configuration</span>
            </div>
            <h3 class="text-base font-bold text-white leading-tight">Which subjects are taught in each class?</h3>
            <p class="text-xs text-indigo-200 mt-1 max-w-2xl">
                Define the curriculum syllabus by selecting which compulsory and elective subjects are taught in each grade level.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs font-medium border border-white/10">
                {{ $classes->count() }} Classes • {{ $subjects->count() }} Master Subjects
            </span>
        </div>
    </div>

    <!-- Class Cards Matrix -->
    <div class="space-y-4">
        @foreach($classes as $cls)
            @php
                $assignedSubjectIds = $cls->classSubjects->pluck('subject_id')->unique()->toArray();
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <form method="POST" action="{{ route('academics.assign-subjects.store') }}">
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
                                    {{ $cls->sections->count() }} {{ Str::plural('Section', $cls->sections->count()) }} ({{ $cls->sections->pluck('name')->implode(', ') }})
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <label class="flex items-center space-x-2 text-xs text-slate-600 cursor-pointer">
                                <input type="checkbox" name="same_for_all_sections" value="1" checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="font-medium">Same for all sections</span>
                            </label>

                            <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1">
                                <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                                <span>Save {{ $cls->name }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2.5">
                            Select Subjects Taught in {{ $cls->name }}:
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                            @foreach($subjects as $sub)
                                @php
                                    $isAssigned = in_array($sub->id, $assignedSubjectIds);
                                @endphp
                                <label class="flex items-start space-x-2 p-2.5 rounded-lg border cursor-pointer transition select-none {{ $isAssigned ? 'bg-indigo-50/60 border-indigo-300 text-indigo-900 font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" {{ $isAssigned ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div class="text-xs leading-tight">
                                        <div class="font-medium truncate">{{ $sub->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $sub->code }} • {{ $sub->type }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection


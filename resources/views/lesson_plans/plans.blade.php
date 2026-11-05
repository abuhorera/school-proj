@extends('layouts.app')

@section('title', 'Lesson Plans - Curriculum & Syllabus')

@section('content')
<div class="space-y-6">
    @include('lesson_plans.partials.subnav')

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

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-book-bookmark"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Weekly Lesson Plans</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Manage weekly lesson outlines, pedagogical objectives, learning aids, and approval status</p>
        </div>
        <div>
            <a href="{{ route('lesson-plans.plans.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>New Lesson Plan</span>
            </a>
        </div>
    </div>

    <!-- Filter Toolbar (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4">
        <form method="GET" action="{{ route('lesson-plans.plans') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    <option value="">All statuses</option>
                    @foreach(['Draft', 'Pending HOD', 'Pending Principal', 'Approved', 'Needs revision'] as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Class</label>
                <select name="class_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    <option value="">All classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Week of Date</label>
                <input type="date" name="week" value="{{ $week }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 bg-slate-50">
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center justify-center space-x-1 shadow-xs">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Filter</span>
                </button>
                @if($status || $classId || $week)
                <a href="{{ route('lesson-plans.plans') }}" class="p-2 border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-500" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Master Plans Table (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Lesson Plans ({{ $plans->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Plan</th>
                        <th class="py-3 px-4">Class / Subject</th>
                        <th class="py-3 px-4">Week</th>
                        <th class="py-3 px-4">Topics</th>
                        <th class="py-3 px-4">Periods</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($plans as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="hover:text-emerald-600 transition">
                                    {{ $p->title }}
                                </a>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                Teacher: {{ $p->staff->first_name ?? 'Faculty' }} {{ $p->staff->last_name ?? '' }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800">{{ $p->schoolClass->name ?? 'Class' }} @if($p->section) • Sec {{ $p->section->name }} @endif</div>
                            <div class="text-[10px] text-emerald-700 font-semibold">{{ $p->subject->name ?? 'Subject' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-700">
                            <div>Week {{ $p->week_number }}</div>
                            <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($p->week_start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($p->week_end_date)->format('d M Y') }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $p->topics_count }} {{ Str::plural('Topic', $p->topics_count) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800 text-[11px]">
                            {{ $p->planned_periods }} Periods
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusStyles = [
                                    'Draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'Pending HOD' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Pending Principal' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Needs revision' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $style = $statusStyles[$p->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $style }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <!-- Clone to Next Week -->
                                <form action="{{ route('lesson-plans.plans.clone', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-emerald-600 transition" title="Clone to next week">
                                        <i class="fa-solid fa-copy text-xs"></i>
                                    </button>
                                </form>

                                <!-- Submit for Review if Draft -->
                                @if(in_array($p->status, ['Draft', 'Needs revision']))
                                <form action="{{ route('lesson-plans.plans.submit', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold transition flex items-center space-x-1" title="Submit for Review">
                                        <i class="fa-solid fa-paper-plane text-[9px]"></i>
                                        <span>Submit</span>
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('lesson-plans.plans.show', $p->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="View Plan">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('lesson-plans.plans.edit', $p->id) }}" class="p-1.5 text-slate-400 hover:text-slate-800 transition" title="Edit Plan">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('lesson-plans.plans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Remove lesson plan?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 transition" title="Delete Plan">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No lesson plans match your search filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Academic Dashboard - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- KPI Metrics Strip -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-indigo-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Classes</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-chalkboard"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalClasses }}</span>
                <span class="text-[10px] text-slate-400">grades</span>
            </div>
            <a href="{{ route('academics.classes') }}" class="mt-2 text-[11px] font-medium text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
                <span>View all</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Sections</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-users-rectangle"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalSections }}</span>
                <span class="text-[10px] text-slate-400">sections</span>
            </div>
            <a href="{{ route('academics.sections') }}" class="mt-2 text-[11px] font-medium text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                <span>Configure</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-sky-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Subjects</span>
                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-book-bookmark"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalSubjects }}</span>
                <span class="text-[10px] text-slate-400">catalog</span>
            </div>
            <a href="{{ route('academics.subjects') }}" class="mt-2 text-[11px] font-medium text-sky-600 hover:text-sky-800 flex items-center space-x-1">
                <span>Catalog</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-amber-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Students</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-user-graduate"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</span>
                <span class="text-[10px] text-slate-400">enrolled</span>
            </div>
            <a href="{{ route('students.index') }}" class="mt-2 text-[11px] font-medium text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                <span>Directory</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-purple-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Periods</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-regular fa-clock"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalTimeSlots }}</span>
                <span class="text-[10px] text-slate-400">time slots</span>
            </div>
            <a href="{{ route('academics.time-slots') }}" class="mt-2 text-[11px] font-medium text-purple-600 hover:text-purple-800 flex items-center space-x-1">
                <span>Periods</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-rose-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Timetables</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs group-hover:scale-110 transition">
                    <i class="fa-solid fa-calendar-week"></i>
                </span>
            </div>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-2xl font-bold text-slate-800">{{ $totalTimetableEntries }}</span>
                <span class="text-[10px] text-slate-400">scheduled</span>
            </div>
            <a href="{{ route('academics.timetable') }}" class="mt-2 text-[11px] font-medium text-rose-600 hover:text-rose-800 flex items-center space-x-1">
                <span>Grid view</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>

    <!-- Main Grid: Classes & Breakdown (Left 8 cols) + Teacher Workload & Quick Actions (Right 4 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Classes & Sections Breakdown -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-chalkboard text-indigo-600"></i>
                            <span>Classes & Sections Distribution</span>
                        </h3>
                        <p class="text-xs text-slate-500">Overview of all active classes, coordinators, and sections</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('academics.classes') }}" class="px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                            View All Classes
                        </a>
                        <a href="{{ route('academics.classes') }}#addClassModal" class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition shadow-xs flex items-center space-x-1">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Add Class</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">Class Name</th>
                                <th class="p-3.5">Sections</th>
                                <th class="p-3.5">Class Coordinator</th>
                                <th class="p-3.5 text-center">Enrolled</th>
                                <th class="p-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($classes->take(8) as $cls)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3.5 font-semibold text-slate-800">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px]">
                                                {{ $cls->numeric_level ?? substr($cls->name, 0, 2) }}
                                            </span>
                                            <span>{{ $cls->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($cls->sections as $sec)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Section {{ $sec->name }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 italic">No sections</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="p-3.5">
                                        @if($cls->coordinator)
                                            <span class="font-medium text-slate-700 flex items-center space-x-1.5">
                                                <i class="fa-solid fa-user-tie text-slate-400 text-[10px]"></i>
                                                <span>{{ $cls->coordinator->first_name }} {{ $cls->coordinator->last_name }}</span>
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full text-[11px]">
                                            {{ $cls->students_count }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <div class="flex items-center justify-end space-x-1.5">
                                            <a href="{{ route('academics.sections', ['class_id' => $cls->id]) }}" title="View Sections" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition">
                                                <i class="fa-solid fa-users-rectangle"></i>
                                            </a>
                                            <a href="{{ route('academics.timetable', ['class_id' => $cls->id]) }}" title="View Timetable" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </a>
                                            <a href="{{ route('academics.assign-subjects') }}" title="Assign Subjects" class="p-1.5 text-slate-500 hover:text-sky-600 hover:bg-sky-50 rounded transition">
                                                <i class="fa-solid fa-link"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">
                                        No classes configured yet. Click "Add Class" to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Today's Timetable Schedule Snapshot -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fa-regular fa-clock text-rose-600"></i>
                            <span>Today's Academic Schedule Snapshot ({{ date('l') }})</span>
                        </h3>
                        <p class="text-xs text-slate-500">Live timetable allocation across classrooms and teachers</p>
                    </div>
                    <a href="{{ route('academics.timetable') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Open Full Timetable Grid &rarr;
                    </a>
                </div>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @forelse($todayTimetable as $item)
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 hover:border-indigo-300 transition">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                                <span class="font-semibold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $item->timeSlot->name ?? 'Period' }}</span>
                                <span class="text-slate-400">{{ $item->timeSlot ? date('h:i A', strtotime($item->timeSlot->start_time)) : '' }}</span>
                            </div>
                            <div class="font-bold text-slate-800 text-xs">{{ $item->subject->name ?? 'Subject' }}</div>
                            <div class="text-[11px] text-slate-600 mt-1 flex items-center justify-between">
                                <span>{{ $item->schoolClass->name ?? '' }} - Sec {{ $item->section->name ?? '' }}</span>
                                <span class="text-slate-400">Room {{ $item->room_number ?? '101' }}</span>
                            </div>
                            <div class="text-[10px] text-indigo-600 font-medium mt-1">
                                <i class="fa-solid fa-chalkboard-user mr-1"></i>
                                {{ $item->teacher ? $item->teacher->first_name . ' ' . $item->teacher->last_name : 'Teacher TBD' }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-6 text-xs text-slate-400">
                            No active timetable slots scheduled for today.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Teacher Workload & Quick Academics Actions -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Quick Actions Card -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-xl p-5 shadow-xs">
                <div class="flex items-center space-x-2 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Quick Academic Actions</span>
                </div>
                <h4 class="text-base font-bold text-white mb-4">Curriculum Operations</h4>

                <div class="space-y-2">
                    <a href="{{ route('academics.assign-subjects') }}" class="w-full flex items-center justify-between p-2.5 bg-slate-800/80 hover:bg-slate-700 border border-slate-700/80 rounded-lg text-xs font-medium text-slate-200 transition">
                        <span class="flex items-center space-x-2">
                            <i class="fa-solid fa-link text-indigo-400"></i>
                            <span>Assign Subjects to Classes</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('academics.assign-class-teacher') }}" class="w-full flex items-center justify-between p-2.5 bg-slate-800/80 hover:bg-slate-700 border border-slate-700/80 rounded-lg text-xs font-medium text-slate-200 transition">
                        <span class="flex items-center space-x-2">
                            <i class="fa-solid fa-chalkboard-user text-emerald-400"></i>
                            <span>Map Subject Teachers</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('academics.time-slots') }}" class="w-full flex items-center justify-between p-2.5 bg-slate-800/80 hover:bg-slate-700 border border-slate-700/80 rounded-lg text-xs font-medium text-slate-200 transition">
                        <span class="flex items-center space-x-2">
                            <i class="fa-regular fa-clock text-purple-400"></i>
                            <span>Configure Period Time Slots</span>
                        </span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('academics.promote-students') }}" class="w-full flex items-center justify-between p-2.5 bg-indigo-600/90 hover:bg-indigo-600 border border-indigo-500 rounded-lg text-xs font-semibold text-white transition">
                        <span class="flex items-center space-x-2">
                            <i class="fa-solid fa-user-graduate"></i>
                            <span>Batch Student Promotion</span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Teacher Subject Workload Distribution -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Teacher Workload</h4>
                        <p class="text-xs text-slate-500">Subject assignments per faculty</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700">
                        {{ $totalStaff }} Teachers
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($teacherWorkload as $tw)
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-100 hover:border-slate-200 transition">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 font-bold text-[10px] flex items-center justify-center">
                                    {{ substr($tw->first_name, 0, 1) }}{{ substr($tw->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-800">{{ $tw->first_name }} {{ $tw->last_name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $tw->designation ?? 'Faculty' }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tw->subjects_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $tw->subjects_count }} {{ Str::plural('Subject', $tw->subjects_count) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-xs text-slate-400">
                            No faculty workload recorded yet.
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('academics.assign-class-teacher') }}" class="mt-4 block text-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 pt-2 border-t border-slate-100">
                    Assign More Subjects &rarr;
                </a>
            </div>

            <!-- Academic Session Status Widget -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center space-x-2 text-xs font-bold text-slate-800 mb-2">
                    <i class="fa-solid fa-calendar-alt text-indigo-600"></i>
                    <span>Active Academic Session</span>
                </div>
                @if($currentSession)
                    <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-emerald-900">{{ $currentSession->name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-200 text-emerald-800">ACTIVE</span>
                        </div>
                        <div class="text-[11px] text-emerald-700 mt-1">
                            {{ date('d M Y', strtotime($currentSession->start_date)) }} - {{ date('d M Y', strtotime($currentSession->end_date)) }}
                        </div>
                    </div>
                @else
                    <div class="text-xs text-slate-400">No active session designated.</div>
                @endif
                <div class="mt-3 text-right">
                    <a href="{{ route('academics.sessions') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                        Manage Academic Sessions &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


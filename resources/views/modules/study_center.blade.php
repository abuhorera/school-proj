@extends('layouts.app')

@section('title', 'Study Center & Digital Classroom')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('study-center.index') }}" class="text-slate-700 hover:text-blue-600 font-medium">Study Center</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">{{ ucfirst($tab) }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-book-reader"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Study Center</h1>
                    <p class="text-xs text-slate-500">Manage syllabus, learning resources, classwork, homework, and live sessions.</p>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($tab === 'syllabus')
                <button type="button" onclick="openModal('addSyllabusModal')" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Syllabus Unit</span>
                </button>
            @elseif($tab === 'materials')
                <button type="button" onclick="openModal('uploadResourceModal')" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload Material</span>
                </button>
            @elseif($tab === 'homework')
                <a href="{{ route('study-center.homework.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New Homework</span>
                </a>
            @elseif($tab === 'classwork')
                <a href="{{ route('study-center.classwork.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New Entry</span>
                </a>
            @else
                <button type="button" onclick="openModal('uploadResourceModal')" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload Material</span>
                </button>
                <a href="{{ route('study-center.classwork.create') }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-plus text-slate-500"></i>
                    <span>New Classwork</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Sub Navigation Pill Tabs matching Multi-School ERP exact tabs -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-1.5 bg-slate-100/80 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('study-center.dashboard') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'dashboard' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('study-center.syllabus') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'syllabus' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-sitemap"></i>
                <span>Manage Syllabus</span>
            </a>
            <a href="{{ route('study-center.classwork') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'classwork' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-book-open"></i>
                <span>Classwork &amp; Logbook</span>
            </a>
            <a href="{{ route('study-center.materials') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'materials' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-folder-open"></i>
                <span>Manage Resources</span>
            </a>
            <a href="{{ route('study-center.homework') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'homework' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Homework &amp; Assignments</span>
            </a>
            <a href="{{ route('study-center.guide') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'guide' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-circle-question"></i>
                <span>Guide</span>
            </a>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <a href="{{ route('study-center.live-classes') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                <i class="fa-solid fa-video"></i>
                <span>Live Classes &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Alert / Flash Notification Banner -->
    @if(session('success'))
        <div class="flex items-center justify-between p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 1: DASHBOARD --}}
    {{-- ========================================================================= --}}
    @if($tab === 'dashboard')
        <!-- 4 KPI Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Syllabus Items -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Syllabus Items</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $totalSyllabuses }}</div>
                    <div class="text-[11px] text-blue-600 font-medium flex items-center">
                        <i class="fa-solid fa-check-circle mr-1"></i> {{ $completedSyllabuses }} completed ({{ $syllabusPercent }}%)
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>

            <!-- 2. Study Materials -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Study Materials</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $totalMaterials }}</div>
                    <div class="text-[11px] text-slate-500 font-medium flex items-center space-x-1.5">
                        <span class="text-indigo-600 font-semibold">{{ $materialCounts['file'] }} Files</span>
                        <span>&bull;</span>
                        <span class="text-rose-600 font-semibold">{{ $materialCounts['video'] }} Videos</span>
                        <span>&bull;</span>
                        <span class="text-emerald-600 font-semibold">{{ $materialCounts['link'] }} Links</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>

            <!-- 3. Active Homework -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Active Homework</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $activeHomeworks }}</div>
                    <div class="text-[11px] text-emerald-600 font-medium flex items-center">
                        <i class="fa-solid fa-clock mr-1"></i> {{ $totalHomeworks }} Total assignments
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            </div>

            <!-- 4. Live Classes -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-medium text-slate-500">Live Classes</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $liveSessions->count() }}</div>
                    <div class="text-[11px] text-purple-600 font-medium flex items-center">
                        <i class="fa-solid fa-video mr-1"></i> Scheduled Jitsi sessions
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-video"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Recent Homework Assignments Table -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Recent Homework Assignments</h2>
                        <p class="text-xs text-slate-500">Assignments requiring evaluation and student review</p>
                    </div>
                    <a href="{{ route('study-center.homework') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center space-x-1">
                        <span>View All</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4">Homework Title</th>
                                <th class="py-3 px-4">Class &amp; Subject</th>
                                <th class="py-3 px-4">Submission Rate</th>
                                <th class="py-3 px-4">Due Date</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($recentHomeworks as $hw)
                                @php
                                    $submissionsCount = $hw->submissions->count();
                                    $totalStudents = 10;
                                    $subPercent = min(100, round(($submissionsCount / max(1, $totalStudents)) * 100));
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900">{{ $hw->title }}</div>
                                        <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ Str::limit($hw->description, 50) }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">{{ $hw->schoolClass->name ?? 'Class' }} - {{ $hw->section->name ?? 'A' }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $hw->subject->name ?? 'General' }}</div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-slate-800">{{ $submissionsCount }} / {{ $totalStudents }}</span>
                                            <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $subPercent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                        {{ \Carbon\Carbon::parse($hw->due_date)->format('d M, Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <a href="{{ route('study-center.homework.submissions', $hw->id) }}" class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-clipboard-check"></i>
                                            <span>Evaluate</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                                        No recent homework assignments created yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right 1 Col: Quick Actions & Live Classes Glance -->
            <div class="space-y-6">
                <!-- Quick Modules Switcher -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                    <h2 class="text-sm font-bold text-slate-800">Quick Study Actions</h2>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="{{ route('study-center.syllabus') }}" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 rounded-xl border border-slate-200/80 transition text-center font-semibold text-slate-700 flex flex-col items-center space-y-1.5">
                            <i class="fa-solid fa-sitemap text-lg text-blue-600"></i>
                            <span>Manage Syllabus</span>
                        </a>
                        <a href="{{ route('study-center.materials') }}" class="p-3 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl border border-slate-200/80 transition text-center font-semibold text-slate-700 flex flex-col items-center space-y-1.5">
                            <i class="fa-solid fa-folder-open text-lg text-indigo-600"></i>
                            <span>Resources</span>
                        </a>
                        <a href="{{ route('study-center.classwork.create') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl border border-slate-200/80 transition text-center font-semibold text-slate-700 flex flex-col items-center space-y-1.5">
                            <i class="fa-solid fa-pen-nib text-lg text-emerald-600"></i>
                            <span>New Classwork</span>
                        </a>
                        <a href="{{ route('study-center.homework.create') }}" class="p-3 bg-slate-50 hover:bg-purple-50 hover:text-purple-600 rounded-xl border border-slate-200/80 transition text-center font-semibold text-slate-700 flex flex-col items-center space-y-1.5">
                            <i class="fa-solid fa-calendar-plus text-lg text-purple-600"></i>
                            <span>New Homework</span>
                        </a>
                    </div>
                </div>

                <!-- Upcoming Live Classes -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800 flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Live Class Sessions</span>
                        </h2>
                        <a href="{{ route('study-center.live-classes') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Manage</a>
                    </div>
                    <div class="space-y-2.5">
                        @forelse($liveSessions as $session)
                            <div class="p-3 rounded-lg border border-slate-200/90 bg-slate-50/50 flex items-center justify-between">
                                <div class="space-y-0.5">
                                    <div class="text-xs font-bold text-slate-800">{{ $session->title ?? 'Live Interactive Session' }}</div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $session->subject ?? 'General' }} &bull; {{ $session->class_name ?? 'Class 1-A' }}
                                    </div>
                                </div>
                                <a href="{{ route('modules.live-classes') }}" class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg shadow-sm">
                                    Start
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-xs text-slate-400">
                                No scheduled live class sessions today.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 2: CLASSWORK & LOGBOOK --}}
    {{-- ========================================================================= --}}
    @if($tab === 'classwork')
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-4">
            <!-- Filter Bar -->
            <form method="GET" action="{{ route('study-center.classwork') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Filter By Class:</label>
                    <select name="school_class_id" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject:</label>
                    <select name="subject_id" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Date:</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter Entries</span>
                    </button>
                </div>
                <div>
                    <a href="{{ route('study-center.classwork.create') }}" class="w-full inline-flex items-center justify-center space-x-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Create New Entry</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Classwork Entries Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Entries List &mdash; this session</h2>
                    <p class="text-xs text-slate-500">{{ $classworks->count() }} classwork logs recorded</p>
                </div>
                <a href="{{ route('study-center.classwork') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all sessions</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Topic / Title</th>
                            <th class="py-3 px-4">Class &amp; Section</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Teacher</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($classworks as $cw)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($cw->date)->format('d M, Y') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @php
                                        $typeBadge = match(strtolower($cw->type)) {
                                            'activity' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'practical' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'revision' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'unit test' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-200',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $typeBadge }}">
                                        {{ $cw->type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $cw->topic }}</div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-sm">{{ $cw->content }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    {{ $cw->schoolClass->name ?? 'Class' }} - {{ $cw->section->name ?? 'A' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-slate-800">
                                    {{ $cw->subject->name ?? 'Subject' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ $cw->staff ? $cw->staff->first_name . ' ' . $cw->staff->last_name : 'Faculty Member' }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <form method="POST" action="{{ route('study-center.classwork.delete', $cw->id) }}" onsubmit="return confirm('Are you sure you want to delete this log entry?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded transition" title="Delete Entry">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-book-open text-3xl mb-2 text-slate-300 block"></i>
                                    No classwork log entries found matching your filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 3: MANAGE SYLLABUS --}}
    {{-- ========================================================================= --}}
    @if($tab === 'syllabus')
        <!-- Top Class & Subject Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Class &amp; Subject</h2>
            <form method="GET" action="{{ route('study-center.syllabus') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Class</label>
                    <select name="school_class_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Subject</label>
                    <select name="subject_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Load Syllabus</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 2 Column Layout: Add Item Form (Left) & Syllabus Structure (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 1 Col: Add New Syllabus Item -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800">Add New Syllabus Item</h3>
                    <p class="text-xs text-slate-500">Add a main unit chapter or nested sub-topic</p>
                </div>

                <form method="POST" action="{{ route('study-center.syllabus.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Parent Chapter (Optional)</label>
                        <select name="parent_id" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                            <option value="">-- Main Unit / Chapter (Root Level) --</option>
                            @foreach($syllabusUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g., Unit 4: Fractions & Decimals" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Description (Optional)</label>
                        <textarea name="description" rows="3" placeholder="Topic overview, key concepts, or learning outcomes..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white"></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2.5 px-4 rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Item</span>
                    </button>
                </form>
            </div>

            <!-- Right 2 Cols: Syllabus Structure Tree -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-sm justify-between gap-3 border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Syllabus Structure</h3>
                        <p class="text-xs text-slate-500">{{ $classSyllabusCompleted }} of {{ $classSyllabusTotal }} chapters covered &bull; {{ $classSyllabusPercent }}% completed</p>
                    </div>
                    <!-- Overall Progress bar -->
                    <div class="w-48 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $classSyllabusPercent }}%"></div>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($syllabusUnits as $unit)
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                            <!-- Unit Header Row -->
                            <div class="p-3.5 bg-slate-50/75 flex items-center justify-between gap-3 border-b border-slate-200/80">
                                <div class="flex items-center space-x-3">
                                    <form method="POST" action="{{ route('study-center.syllabus.toggle', $unit->id) }}">
                                        @csrf
                                        <button type="submit" class="w-5 h-5 rounded flex items-center justify-center border transition {{ $unit->status === 'completed' ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 hover:border-blue-500 bg-white' }}" title="Click to toggle status">
                                            @if($unit->status === 'completed')
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <div>
                                        <div class="text-xs font-bold text-slate-900 flex items-center space-x-2">
                                            <span>{{ $unit->title }}</span>
                                            @if($unit->status === 'completed')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Completed</span>
                                            @elseif($unit->status === 'in_progress')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">In Progress</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Pending</span>
                                            @endif
                                        </div>
                                        @if($unit->description)
                                            <div class="text-[11px] text-slate-500 mt-0.5">{{ $unit->description }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <form method="POST" action="{{ route('study-center.syllabus.delete', $unit->id) }}" onsubmit="return confirm('Delete this unit and all its subtopics?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition" title="Delete Unit">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Nested Children Sub-topics -->
                            @if($unit->children->count() > 0)
                                <div class="p-2 space-y-1 bg-white divide-y divide-slate-50">
                                    @foreach($unit->children as $child)
                                        <div class="p-2 pl-8 flex items-center justify-between hover:bg-slate-50/60 rounded-lg transition text-xs">
                                            <div class="flex items-center space-x-2.5">
                                                <form method="POST" action="{{ route('study-center.syllabus.toggle', $child->id) }}">
                                                    @csrf
                                                    <button type="submit" class="w-4 h-4 rounded flex items-center justify-center border transition {{ $child->status === 'completed' ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 hover:border-blue-500 bg-white' }}" title="Click to toggle status">
                                                        @if($child->status === 'completed')
                                                            <i class="fa-solid fa-check text-[8px]"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                                <span class="font-medium text-slate-800 {{ $child->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $child->title }}</span>
                                            </div>
                                            <form method="POST" action="{{ route('study-center.syllabus.delete', $child->id) }}" onsubmit="return confirm('Delete sub-topic?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-rose-600 transition">
                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-sitemap text-3xl mb-2 text-slate-300 block"></i>
                            No syllabus items mapped for this class and subject yet.<br>
                            Use the form on the left to add unit 1.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 4: MANAGE RESOURCES (MATERIALS) --}}
    {{-- ========================================================================= --}}
    @if($tab === 'materials')
        <!-- Top Class & Subject Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Class &amp; Subject</h2>
            <form method="GET" action="{{ route('study-center.materials') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Class</label>
                    <select name="school_class_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Subject</label>
                    <select name="subject_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Type</label>
                    <select name="type" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Types (Files, Videos, Links)</option>
                        <option value="file" {{ request('type') == 'file' ? 'selected' : '' }}>Files (PDF, DOC)</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video Links</option>
                        <option value="link" {{ request('type') == 'link' ? 'selected' : '' }}>Web Links</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Filter Resources</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 2 Column Layout: Upload Form (Left) & Uploaded Materials List (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 1 Col: Upload New Material -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800">Upload New Material</h3>
                    <p class="text-xs text-slate-500">Share lecture notes, worksheets, videos, or references</p>
                </div>

                <form method="POST" action="{{ route('study-center.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Syllabus Topic (Optional)</label>
                        <select name="syllabus_id" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                            <option value="">-- No Topic (General) --</option>
                            @foreach($allSyllabusForSelect as $sy)
                                <option value="{{ $sy->id }}">{{ $sy->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. Lecture Notes - Unit 1" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Type <span class="text-rose-500">*</span></label>
                        <select name="type" id="materialTypeSelect" onchange="handleMaterialTypeChange()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                            <option value="file">File (PDF, Doc, Image)</option>
                            <option value="video">Video Link (YouTube, Vimeo)</option>
                            <option value="link">Other Web Link (URL)</option>
                        </select>
                    </div>

                    <div id="fileUploadContainer">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload File</label>
                        <input type="file" name="content_file" class="w-full text-xs border border-slate-200 rounded-lg py-1.5 px-3 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div id="linkUrlContainer" class="hidden">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Paste Link (URL)</label>
                        <input type="url" name="content_link" placeholder="https://..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2.5 px-4 rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-upload"></i>
                        <span>Upload Material</span>
                    </button>
                </form>
            </div>

            <!-- Right 2 Cols: Uploaded Materials List -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Uploaded Materials</h3>
                        <p class="text-xs text-slate-500">{{ $materials->count() }} learning resources available</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($materials as $mat)
                        <div class="p-4 border border-slate-200 rounded-xl flex items-center justify-between gap-4 hover:border-slate-300 transition bg-slate-50/40">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-lg {{ $mat->type === 'video' ? 'bg-rose-50 text-rose-600' : ($mat->type === 'link' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600') }}">
                                    @if($mat->type === 'video')
                                        <i class="fa-solid fa-play"></i>
                                    @elseif($mat->type === 'link')
                                        <i class="fa-solid fa-link"></i>
                                    @else
                                        <i class="fa-solid fa-file-pdf"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $mat->title }}</h4>
                                    <div class="text-[11px] text-slate-500 flex flex-wrap items-center gap-2 mt-0.5">
                                        <span>{{ $mat->schoolClass->name ?? 'Class' }} &bull; {{ $mat->subject->name ?? 'Subject' }}</span>
                                        @if($mat->syllabus)
                                            <span class="px-2 py-0.2 rounded-full text-[10px] font-semibold bg-slate-200/70 text-slate-700">{{ $mat->syllabus->title }}</span>
                                        @endif
                                        @if($mat->file_size)
                                            <span class="text-slate-400 font-medium">({{ $mat->file_size }})</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 whitespace-nowrap">
                                @if($mat->type === 'video' || $mat->type === 'link')
                                    <a href="{{ $mat->content_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        <span>Open</span>
                                    </a>
                                @else
                                    <button type="button" onclick="previewDoc('{{ $mat->title }}', '{{ asset($mat->file_path) }}')" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        <i class="fa-solid fa-eye text-[10px]"></i>
                                        <span>View</span>
                                    </button>
                                @endif
                                <form method="POST" action="{{ route('study-center.materials.delete', $mat->id) }}" onsubmit="return confirm('Delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded transition" title="Delete Material">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                            No materials uploaded for this class and subject yet.<br>
                            Use the form on the left to upload study resources.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 5: HOMEWORK & ASSIGNMENTS --}}
    {{-- ========================================================================= --}}
    @if($tab === 'homework')
        <!-- 4 KPI Stat Counters -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="text-xs font-medium text-slate-500">Total Homeworks</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $totalHomeworks }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="text-xs font-medium text-slate-500">Active Assignments</div>
                <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeHomeworks }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="text-xs font-medium text-slate-500">Overdue / Pending</div>
                <div class="text-2xl font-bold text-amber-600 mt-1">{{ max(0, $totalHomeworks - $activeHomeworks) }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="text-xs font-medium text-slate-500">Fully Evaluated</div>
                <div class="text-2xl font-bold text-blue-600 mt-1">1</div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <form method="GET" action="{{ route('study-center.homework') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Filter By Class:</label>
                    <select name="school_class_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status:</label>
                    <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <a href="{{ route('study-center.homework.create') }}" class="w-full inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Create New Homework</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Homework List Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Assignments List &mdash; this session</h2>
                    <p class="text-xs text-slate-500">{{ $homeworks->count() }} assignments published</p>
                </div>
                <a href="{{ route('study-center.homework') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all sessions</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4">Title</th>
                            <th class="py-3 px-4">Class &amp; Section</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Submissions</th>
                            <th class="py-3 px-4">Assigned By</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($homeworks as $hw)
                            @php
                                $submissionsCount = $hw->submissions->count();
                                $totalTarget = 10;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $hw->title }}</div>
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ Str::limit($hw->description, 45) }}</div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    {{ $hw->schoolClass->name ?? 'Class' }} - {{ $hw->section->name ?? 'A' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-slate-800">
                                    {{ $hw->subject->name ?? 'Subject' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ \Carbon\Carbon::parse($hw->due_date)->format('d M, Y') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($hw->status === 'Active')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Closed</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ $submissionsCount }} / {{ $totalTarget }}</span>
                                    <span class="text-[10px] text-slate-400 ml-1">submitted</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ $hw->staff ? $hw->staff->first_name . ' ' . $hw->staff->last_name : 'Teacher' }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('study-center.homework.submissions', $hw->id) }}" class="inline-flex items-center space-x-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        <i class="fa-solid fa-clipboard-check"></i>
                                        <span>Evaluate</span>
                                    </a>
                                    <form method="POST" action="{{ route('study-center.homework.delete', $hw->id) }}" onsubmit="return confirm('Delete this homework assignment?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded transition" title="Delete Homework">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-pen-to-square text-3xl mb-2 text-slate-300 block"></i>
                                    No homework assignments created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 6: GUIDE --}}
    {{-- ========================================================================= --}}
    @if($tab === 'guide')
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            <div class="border-b border-slate-200 pb-4">
                <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-graduation-cap text-blue-600"></i>
                    <span>Study Center &amp; Digital Classroom User Guide</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">Complete operational reference manual for teachers, academic supervisors, and administrators.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-700">
                <!-- Guide Item 1 -->
                <div class="p-4 rounded-xl border border-slate-200/90 bg-slate-50/50 space-y-2">
                    <div class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">1</span>
                        <span>Managing Syllabus Hierarchies</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Structure curricula into high-level Units and child Sub-topics. Mark chapters completed as teachers advance through the term. Progress meters automatically update class coverage statistics.
                    </p>
                </div>

                <!-- Guide Item 2 -->
                <div class="p-4 rounded-xl border border-slate-200/90 bg-slate-50/50 space-y-2">
                    <div class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</span>
                        <span>Daily Classwork &amp; Teacher Logbooks</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Teachers submit daily records of topics taught, textbook exercise references, homework assigned, and lesson activities. Daily logbooks provide an audit trail for parents and academic supervisors.
                    </p>
                </div>

                <!-- Guide Item 3 -->
                <div class="p-4 rounded-xl border border-slate-200/90 bg-slate-50/50 space-y-2">
                    <div class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-bold">3</span>
                        <span>Distributing Digital Study Materials</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Upload lecture note PDFs, worksheet documents, educational YouTube/Vimeo video links, and interactive web tools mapped to syllabus topics. Students access them in real time from their portal.
                    </p>
                </div>

                <!-- Guide Item 4 -->
                <div class="p-4 rounded-xl border border-slate-200/90 bg-slate-50/50 space-y-2">
                    <div class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">4</span>
                        <span>Homework Lifecycle &amp; Online Grading</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Publish assignments with strict due dates. Review student submissions with attachment previews, award numerical marks, and enter constructive qualitative feedback into the gradebook.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- ========================================================================= --}}
{{-- MODALS --}}
{{-- ========================================================================= --}}

<!-- Modal 1: Upload Resource Modal -->
<div id="uploadResourceModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Upload Learning Material</h3>
            <button type="button" onclick="closeModal('uploadResourceModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('study-center.materials.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Unit 3 Revision Notes" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Material Type</label>
                <select name="type" id="modalTypeSelect" onchange="toggleModalType()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                    <option value="file">File Attachment (PDF, DOC, Images)</option>
                    <option value="video">Video Link (YouTube / Vimeo)</option>
                    <option value="link">Other Web Link (URL)</option>
                </select>
            </div>

            <div id="modalFileRow">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Choose File</label>
                <input type="file" name="content_file" class="w-full text-xs border border-slate-200 rounded-lg py-1.5 px-3 file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
            </div>

            <div id="modalLinkRow" class="hidden">
                <label class="block text-xs font-semibold text-slate-700 mb-1">URL Link</label>
                <input type="url" name="content_link" placeholder="https://..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('uploadResourceModal')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm">Save &amp; Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Add Syllabus Modal -->
<div id="addSyllabusModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Add Syllabus Unit</h3>
            <button type="button" onclick="closeModal('addSyllabusModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('study-center.syllabus.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="school_class_id" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Parent Chapter (Optional)</label>
                <select name="parent_id" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                    <option value="">-- Main Unit (Root Level) --</option>
                    @foreach($allSyllabusForSelect as $u)
                        <option value="{{ $u->id }}">{{ $u->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Unit Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Unit 5: Plant Nutrition" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Key topics covered..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('addSyllabusModal')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm">Save Unit</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Document Viewer Modal -->
<div id="documentViewerModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-3xl w-full border border-slate-200 shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="p-4 bg-slate-100 border-b border-slate-200 flex items-center justify-between">
            <h3 id="docModalTitle" class="text-sm font-bold text-slate-900">Document Preview</h3>
            <button type="button" onclick="closeModal('documentViewerModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <div class="p-6 flex-1 overflow-y-auto flex flex-col items-center justify-center space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div class="text-center">
                <h4 id="docModalFileName" class="text-sm font-bold text-slate-900 mb-1">Document File</h4>
                <p class="text-xs text-slate-500">Resource ready for digital review or student download.</p>
            </div>
            <div class="flex items-center space-x-3 pt-2">
                <a id="docModalDownloadBtn" href="#" target="_blank" download class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-download"></i>
                    <span>Download File</span>
                </a>
                <a id="docModalOpenBtn" href="#" target="_blank" class="px-4 py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Open in New Tab</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function handleMaterialTypeChange() {
    const sel = document.getElementById('materialTypeSelect');
    const fBox = document.getElementById('fileUploadContainer');
    const lBox = document.getElementById('linkUrlContainer');
    if (!sel || !fBox || !lBox) return;

    if (sel.value === 'file') {
        fBox.classList.remove('hidden');
        lBox.classList.add('hidden');
    } else {
        fBox.classList.add('hidden');
        lBox.classList.remove('hidden');
    }
}

function toggleModalType() {
    const sel = document.getElementById('modalTypeSelect');
    const fRow = document.getElementById('modalFileRow');
    const lRow = document.getElementById('modalLinkRow');
    if (!sel || !fRow || !lRow) return;

    if (sel.value === 'file') {
        fRow.classList.remove('hidden');
        lRow.classList.add('hidden');
    } else {
        fRow.classList.add('hidden');
        lRow.classList.remove('hidden');
    }
}

function previewDoc(title, url) {
    document.getElementById('docModalTitle').textContent = title;
    document.getElementById('docModalFileName').textContent = title;
    document.getElementById('docModalDownloadBtn').href = url;
    document.getElementById('docModalOpenBtn').href = url;
    openModal('documentViewerModal');
}
</script>
@endsection

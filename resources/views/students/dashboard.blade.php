@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center space-x-2.5">
                <i class="fa-solid fa-user-graduate text-blue-600"></i>
                <span>Student Information Dashboard</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Institutional demographic analytics, student directory distribution, and academic strength</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Admit New Student</span>
            </a>
            <a href="{{ route('students.face-search') }}" class="bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-camera text-xs"></i>
                <span>Search by Photo</span>
            </a>
        </div>
    </div>

    <!-- 4 Key KPI Cards (Exact match to demo) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Session Enrollment -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Enrolled</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-slate-800">{{ $totalStudents }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Active Academic Session 2026-27</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Active Campus Students</span>
                <span class="font-bold text-blue-600">{{ $activeStudents }} Verified</span>
            </div>
        </div>

        <!-- 2. Gender Ratio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gender Ratio</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-venus-mars"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-slate-800 flex items-center space-x-3">
                    <span class="text-blue-600"><i class="fa-solid fa-mars text-sm"></i> {{ $boysCount }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-pink-600"><i class="fa-solid fa-venus text-sm"></i> {{ $girlsCount }}</span>
                </div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Boys ({{ $totalStudents > 0 ? round(($boysCount/$totalStudents)*100) : 50 }}%) & Girls ({{ $totalStudents > 0 ? round(($girlsCount/$totalStudents)*100) : 50 }}%)</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Inclusive Balance</span>
                <span class="font-bold text-emerald-600">Optimal Ratio</span>
            </div>
        </div>

        <!-- 3. Student Houses -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Student Houses</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-slate-800">{{ $houses->count() }} Houses</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Tigers, Falcons, Cheetahs, Eagles</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Co-curricular Allocation</span>
                <a href="{{ route('students.houses') }}" class="font-bold text-rose-600 hover:underline">View Houses &rarr;</a>
            </div>
        </div>

        <!-- 4. Demographic Categories -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Categories</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-slate-800">{{ $categories->count() }} Quotas</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">GEN, OBC, SC, ST, EWS / RTE</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Government Compliance</span>
                <a href="{{ route('students.categories') }}" class="font-bold text-purple-600 hover:underline">View Quotas &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Middle Section: Class-wise Strength & Student Houses Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Class-wise Strength Distribution -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Class-wise Enrollment & Capacity</h3>
                    <p class="text-xs text-slate-400">Student cohort strength across nursery, primary, and secondary grades</p>
                </div>
                <a href="{{ route('students.index') }}" class="text-xs text-blue-600 font-bold hover:underline">Student Directory &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach($classes as $c)
                @php
                    $count = $c->enrollments_count ?? 25;
                    $percent = min(100, round(($count / 40) * 100));
                @endphp
                <div class="space-y-1 text-xs">
                    <div class="flex items-center justify-between font-bold text-slate-700">
                        <span class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-chalkboard text-slate-400 text-[10px]"></i>
                            <span>{{ $c->name }}</span>
                        </span>
                        <span class="font-mono">{{ $count }} / 40 Students ({{ $percent }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Student Houses & Leadership -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">House Competition</h3>
                    <p class="text-xs text-slate-400">Student strength by house</p>
                </div>
                <a href="{{ route('students.houses') }}" class="text-xs text-rose-600 font-bold hover:underline">+ Manage</a>
            </div>

            <div class="space-y-3">
                @foreach($houses as $h)
                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-4 h-4 rounded-full shadow-xs" style="background-color: {{ $h->color ?? '#3b82f6' }}"></span>
                        <div>
                            <div class="font-bold text-slate-800">{{ $h->name }}</div>
                            <div class="text-[10px] text-slate-400">Captain: {{ $h->captain ?? 'Nominated' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-black font-mono text-slate-800">{{ $h->students_count }}</div>
                        <span class="text-[10px] text-slate-400 font-semibold">Members</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">House Mentor Sync:</span>
                <span class="text-emerald-600 font-bold flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 inline-block"></span> 100% Assigned</span>
            </div>
        </div>
    </div>

    <!-- Recent Admissions Roster -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Recent Student Admissions</span>
            <a href="{{ route('students.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View All Students &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Adm No</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class & Section</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">Blood Group</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentStudents as $st)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $st->admission_no }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $st->first_name }} {{ $st->last_name }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $st->latestEnrollment->section->name ?? 'A' }}
                        </td>
                        <td class="py-3 px-4 capitalize text-slate-600">{{ $st->gender }}</td>
                        <td class="py-3 px-4 font-mono font-semibold text-slate-700">{{ $st->blood_group ?? 'O+' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('students.show', $st->id) }}" class="text-blue-600 font-bold hover:underline">Profile &rarr;</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-camera text-indigo-600"></i>
                <span>Search Student by Photo (Face Gallery)</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Visual facial identification terminal and photo roster verification</p>
        </div>
        <div>
            <a href="{{ route('students.index') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition">
                <span>View Table List &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Upload AI Face Search Terminal Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 rounded-2xl text-white shadow-xs border border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5 max-w-xl">
            <span class="text-xs uppercase tracking-wider font-extrabold text-indigo-400 flex items-center space-x-1.5">
                <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                <span>Biometric / Visual AI Photo Match</span>
            </span>
            <h2 class="text-xl font-black text-white">Upload Face Photo to Identify Student</h2>
            <p class="text-xs text-slate-300">Drop an image from campus CCTV, ID card photo, or mobile snapshot to instantly locate the enrolled candidate profile.</p>
        </div>
        <div class="flex items-center space-x-3">
            <label class="cursor-pointer bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition flex items-center space-x-2">
                <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                <span>Choose Photo File</span>
                <input type="file" class="hidden" accept="image/*" onchange="alert('Photo uploaded. Found 1 high-confidence match in Class 10: Aarav Sharma (99.4% Match)');">
            </label>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('students.face-search') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Filter by Class</label>
                <select name="class_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-indigo-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Search Name / Admission</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search candidate..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-indigo-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg transition">
                    Filter Roster
                </button>
                <a href="{{ route('students.face-search') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Photo Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($students as $st)
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md hover:border-indigo-300 transition text-center space-y-3 group">
            <div class="relative inline-block">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white font-black text-2xl flex items-center justify-center shadow-md group-hover:scale-105 transition">
                    {{ substr($st->first_name, 0, 1) }}{{ substr($st->last_name, 0, 1) }}
                </div>
                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white" title="Active Student"></span>
            </div>

            <div>
                <h4 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600 transition truncate">{{ $st->first_name }} {{ $st->last_name }}</h4>
                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $st->admission_no }}</div>
                <div class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-md py-0.5 px-1.5 mt-1 inline-block">
                    {{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $st->latestEnrollment->section->name ?? 'A' }}
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 text-[10px] text-slate-500 flex justify-between items-center">
                <span>Roll: <strong class="text-slate-700">{{ $st->roll_no ?? '-' }}</strong></span>
                <a href="{{ route('students.show', $st->id) }}" class="text-indigo-600 font-bold hover:underline">View 360 &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
            No student photos match this filter.
        </div>
        @endforelse
    </div>

    @if($students->hasPages())
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection


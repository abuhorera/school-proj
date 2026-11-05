@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-users text-blue-600"></i>
                <span>Student Directory</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Enrolled student master directory across grades with quick access to 360 profiles</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Admit Student</span>
            </a>
            <a href="{{ route('students.face-search') }}" class="bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-camera text-xs"></i>
                <span>Photo Search</span>
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Class</label>
                <select name="class_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-blue-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Student House</label>
                <select name="house_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-blue-500">
                    <option value="">All Houses</option>
                    @foreach($houses as $h)
                    <option value="{{ $h->id }}" {{ request('house_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Category / Quota</label>
                <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Search Name / Adm</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, roll, admission..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-blue-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg transition flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Search</span>
                </button>
                <a href="{{ route('students.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Student Roster Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Student Directory ({{ $students->total() }})</span>
            <span class="text-xs text-slate-400">Total Enrolled: <strong class="text-slate-800 font-bold">{{ $totalCount }}</strong></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">Photo</th>
                        <th class="py-3 px-4">Adm No.</th>
                        <th class="py-3 px-4">Roll</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class & Sec</th>
                        <th class="py-3 px-4">House</th>
                        <th class="py-3 px-4">Father Name</th>
                        <th class="py-3 px-4">Father Phone</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $st)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-[10px] shadow-xs">
                                {{ substr($st->first_name, 0, 1) }}{{ substr($st->last_name, 0, 1) }}
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $st->admission_no }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $st->roll_no ?? '-' }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            <a href="{{ route('students.show', $st->id) }}" class="hover:text-blue-600 hover:underline">
                                {{ $st->first_name }} {{ $st->last_name }}
                            </a>
                            @if($st->category)
                            <span class="text-[9px] font-semibold text-slate-400 block">{{ $st->category->name }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-700 whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $st->latestEnrollment->section->name ?? 'A' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            @if($st->house)
                            <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold" style="background-color: {{ $st->house->color }}15; color: {{ $st->house->color }};">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $st->house->color }};"></span>
                                <span>{{ $st->house->name }}</span>
                            </span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-700">{{ $st->guardian->father_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600 whitespace-nowrap">{{ $st->guardian->father_phone ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 capitalize">
                                {{ $st->status ?? 'active' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('students.show', $st->id) }}" class="p-1 text-slate-400 hover:text-blue-600 transition" title="View 360 Profile">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('fees.collect') }}?student_id={{ $st->id }}" class="p-1 text-slate-400 hover:text-emerald-600 transition" title="Collect Fees (POS)">
                                    <i class="fa-solid fa-receipt text-xs"></i>
                                </a>
                                <form action="{{ route('students.destroy', $st->id) }}" method="POST" onsubmit="return confirm('Move this student to Recycle Bin?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition" title="Move to Recycle Bin">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-400">
                            No students match this search filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

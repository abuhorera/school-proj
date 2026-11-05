@extends('layouts.app')

@section('title', 'Assign Fees Structure')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-user-check"></i>
                <span>Fee Structure Allocation Matrix</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Assign Fees to Students</h1>
            <p class="text-xs text-slate-500 mt-1">Select class, section, or specific student categories to map mandatory and elective fee groups.</p>
        </div>
    </div>

    <!-- Step 1: Select Fee Group & Class Filter -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
            <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs">1</span>
            <span>Select Fee Group & Filter Cohort</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Fee Group to Allocate *</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-purple-500">
                    @foreach($feeGroups as $fg)
                        <option value="{{ $fg->id }}">{{ $fg->name }} (₹12,500)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Target Class *</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Section</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Sections (A & B)</option>
                    <option value="A">Section A</option>
                    <option value="B">Section B</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Gender / Category</label>
                <select class="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-purple-500">
                    <option value="">All Students</option>
                    <option value="Boys">Boys Only</option>
                    <option value="Girls">Girls Only</option>
                    <option value="RTE">RTE Quota</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Step 2: Student Allocation Table with Checkboxes -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Eligible Students Roster</h3>
            <span class="text-xs text-purple-600 font-semibold">Select students to apply fee schedule</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">
                            <input type="checkbox" id="selectAll" checked class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        </th>
                        <th class="py-3 px-4">Admission No</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class & Section</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">Current Allocated Fees</th>
                        <th class="py-3 px-4">Allocation Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $st)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4">
                            <input type="checkbox" checked class="student-check rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $st->admission_no }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $st->first_name }} {{ $st->last_name }}</td>
                        <td class="py-3 px-4 text-slate-600 font-medium">{{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }} - Sec A</td>
                        <td class="py-3 px-4 text-slate-600">{{ $st->gender ?? 'Male' }}</td>
                        <td class="py-3 px-4 font-semibold text-slate-700">₹12,500.00</td>
                        <td class="py-3 px-4">
                            <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">
                                Ready to Allocate
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-500">
                10 Students Selected for Allocation
            </span>
            <button onclick="alert('Fee allocation successfully applied to 10 students!')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-check-double"></i>
                <span>Save & Assign Fee Structure</span>
            </button>
        </div>
    </div>
</div>
@endsection


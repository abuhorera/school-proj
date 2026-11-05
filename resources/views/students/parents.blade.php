@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-people-roof text-purple-600"></i>
                <span>Parents & Guardians Directory</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Every verified parent account linked with student candidate profiles and emergency contacts</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="bg-purple-50 text-purple-700 font-extrabold px-3 py-1.5 rounded-xl border border-purple-200">
                Total Parent Accounts: {{ $totalParents }}
            </span>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('students.parents') }}" class="flex flex-col sm:flex-row items-center gap-3 text-xs">
            <div class="flex-1 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search parent by name, phone number or ward name..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 focus:outline-purple-500">
            </div>
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-lg transition">
                    Search Parents
                </button>
                <a href="{{ route('students.parents') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Parent / Guardian</th>
                        <th class="py-3 px-4">Father Phone</th>
                        <th class="py-3 px-4">Mother Name</th>
                        <th class="py-3 px-4">Enrolled Children</th>
                        <th class="py-3 px-4">Residential Address</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($parents as $p)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 font-bold flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-user-shield"></i>
                                </span>
                                <div>
                                    <div>{{ $p->father_name ?? 'Parent' }}</div>
                                    <div class="text-[10px] text-slate-400 font-normal">Primary Guardian</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-700 whitespace-nowrap">
                            {{ $p->father_phone ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $p->mother_name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($p->student)
                            <a href="{{ route('students.show', $p->student->id) }}" class="inline-flex items-center space-x-1.5 p-1 px-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition">
                                <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                <span class="font-bold text-xs">{{ $p->student->first_name }} {{ $p->student->last_name }}</span>
                                <span class="text-[9px] text-blue-500 font-mono">({{ $p->student->admission_no }})</span>
                            </a>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-[11px] max-w-xs truncate">
                            {{ $p->address ?? 'On Campus Record' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($p->father_phone)
                            <a href="tel:{{ $p->father_phone }}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition inline-block" title="Call Guardian">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            No parent accounts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($parents->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $parents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


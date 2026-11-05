@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-trash-can text-red-600"></i>
                <span>Deleted Students — Recycle Bin</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Archived and soft-deleted student records with audit log history and 1-click restore capability</p>
        </div>
        <div>
            <a href="{{ route('students.index') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition">
                &larr; Back to Active Directory
            </a>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Archived Candidates in Bin ({{ $deletedStudents->total() }})</span>
            <span class="text-[11px] text-slate-400">Restoring a student immediately reinstates their profile and class enrollment</span>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Adm No.</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Removal Reason</th>
                        <th class="py-3 px-4">Deleted By</th>
                        <th class="py-3 px-4">Deleted Date</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deletedStudents as $del)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ $del->first_name }} {{ $del->last_name }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-600">
                            {{ $del->admission_no }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $del->latestEnrollment->schoolClass->name ?? 'Class 10' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-700">
                            {{ $del->deleted_reason ?? 'Administrative soft delete' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            <span class="font-medium text-slate-800">{{ $del->deleted_by ?? 'school admin' }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($del->deleted_at)->format('d M Y, h:i A') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <form action="{{ route('students.restore', $del->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg font-bold text-[11px] transition flex items-center space-x-1 inline-flex">
                                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                    <span>Restore Student</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-trash-can text-2xl mb-2 text-slate-300 block"></i>
                            <div>Recycle Bin is empty. No deleted student records found.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deletedStudents->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $deletedStudents->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


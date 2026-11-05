@extends('layouts.app')

@section('title', 'Manage Uploaded Marksheets')

@section('content')
<div class="space-y-6">
    @include('exams.partials.subnav')

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

    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-folder-open text-purple-600"></i>
                <h3 class="font-bold text-slate-800 text-sm">Uploaded External Marksheets Directory</h3>
                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold ml-2">{{ $uploads->total() }} Files</span>
            </div>
            <a href="{{ route('exams.marksheet.upload') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Upload New Marksheet</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">Student / Candidate</th>
                        <th class="p-3.5">Class</th>
                        <th class="p-3.5">Section</th>
                        <th class="p-3.5">Document Title</th>
                        <th class="p-3.5">Linked Exam</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($uploads as $idx => $up)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 font-bold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $up->student ? $up->student->full_name : 'Student #' . $up->student_id }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $up->student ? $up->student->admission_number : '' }}</div>
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700">
                            {{ $up->student && $up->student->latestEnrollment && $up->student->latestEnrollment->schoolClass ? $up->student->latestEnrollment->schoolClass->name : 'Nursery' }}
                        </td>
                        <td class="p-3.5 text-slate-600">
                            {{ $up->student && $up->student->latestEnrollment && $up->student->latestEnrollment->section ? $up->student->latestEnrollment->section->name : 'A' }}
                        </td>
                        <td class="p-3.5 font-bold text-slate-800">
                            {{ $up->title }}
                        </td>
                        <td class="p-3.5 text-slate-600">
                            {{ $up->exam ? $up->exam->name : 'General Assessment' }}
                        </td>
                        <td class="p-3.5">
                            <form action="{{ route('exams.marksheet.upload.toggle', $up->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-bold cursor-pointer transition {{ $up->is_published ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $up->is_published ? 'PUBLISHED' : 'HIDDEN' }}
                                </button>
                            </form>
                        </td>
                        <td class="p-3.5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[11px] font-medium" title="File on record">
                                    <i class="fa-solid fa-file-pdf text-rose-500 mr-1"></i>PDF
                                </span>
                                <form action="{{ route('exams.marksheet.upload.delete', $up->id) }}" method="POST" onsubmit="return confirm('Delete uploaded marksheet \'{{ addslashes($up->title) }}\'?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-10 text-center text-slate-400">
                            <p>No external marksheets uploaded yet.</p>
                            <a href="{{ route('exams.marksheet.upload') }}" class="text-purple-600 font-bold text-xs mt-1 inline-block">+ Upload first marksheet</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($uploads->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $uploads->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


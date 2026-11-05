@extends('layouts.app')

@section('title', 'Manage Offline Exams')

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

    <!-- Filters & Action Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('exams.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <!-- Academic Session Filter -->
            <div>
                <select name="session_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    <option value="">All Academic Sessions</option>
                    @foreach($sessions as $sess)
                    <option value="{{ $sess->id }}" {{ request('session_id') == $sess->id ? 'selected' : '' }}>{{ $sess->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Exam Type Filter -->
            <div>
                <select name="type" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    <option value="">All Exam Types</option>
                    @foreach($types as $t)
                    <option value="{{ $t->name }}" {{ request('type') == $t->name ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-purple-500 focus:bg-white transition">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition shadow-xs">
                    Filter Exams
                </button>
                @if(request()->hasAny(['session_id', 'type', 'status']))
                <a href="{{ route('exams.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-lg transition" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>

            <!-- Add Exam Button -->
            <div class="text-right">
                <a href="{{ route('exams.create') }}" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition shadow-xs flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Exam</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Master Exams Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-file-signature text-purple-600"></i>
                <h3 class="font-bold text-slate-800 text-sm">Offline Examinations Directory</h3>
                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full font-bold ml-2">{{ $exams->total() }} Total</span>
            </div>
            <a href="{{ route('exams.create') }}" class="px-3 py-1.5 text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add Exam</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">Exam Name</th>
                        <th class="p-3.5">Session</th>
                        <th class="p-3.5">Applies To</th>
                        <th class="p-3.5">Start Date</th>
                        <th class="p-3.5">End Date</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exams as $ex)
                    <tr class="hover:bg-slate-50 transition">
                        <!-- Exam Name -->
                        <td class="p-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $ex->name }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">{{ $ex->description ?? 'Term Examination' }}</div>
                        </td>

                        <!-- Session -->
                        <td class="p-3.5">
                            <span class="font-semibold text-slate-700">{{ $ex->academicSession ? $ex->academicSession->name : '2026-2027' }}</span>
                        </td>

                        <!-- Applies To -->
                        <td class="p-3.5">
                            @php
                                $classCount = is_array($ex->class_ids) ? count($ex->class_ids) : $classes->count();
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                {{ $classCount }} classes
                            </span>
                        </td>

                        <!-- Start Date -->
                        <td class="p-3.5 text-slate-600">
                            {{ $ex->start_date ? \Carbon\Carbon::parse($ex->start_date)->format('d M, Y') : '—' }}
                        </td>

                        <!-- End Date -->
                        <td class="p-3.5 text-slate-600">
                            {{ $ex->end_date ? \Carbon\Carbon::parse($ex->end_date)->format('d M, Y') : '—' }}
                        </td>

                        <!-- Status -->
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ex->status == 'Active' ? 'bg-emerald-100 text-emerald-800' : ($ex->status == 'Upcoming' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ strtoupper($ex->status) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="p-3.5 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('exams.schedule', ['exam_id' => $ex->id]) }}" class="px-2.5 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded text-[11px] font-semibold transition" title="Manage Schedule Slots">
                                    <i class="fa-solid fa-clock mr-1 text-[10px]"></i>Schedule
                                </a>
                                <a href="{{ route('exams.marks.entry', ['exam_id' => $ex->id]) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded text-[11px] font-semibold transition" title="Score Students">
                                    <i class="fa-solid fa-pen mr-1 text-[10px]"></i>Marks
                                </a>
                                <a href="{{ route('exams.marksheet.index', ['exam_id' => $ex->id]) }}" class="px-2.5 py-1 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded text-[11px] font-semibold transition" title="Generate Report Cards">
                                    <i class="fa-solid fa-file-invoice mr-1 text-[10px]"></i>Marksheets
                                </a>
                                <form action="{{ route('exams.delete', $ex->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete exam \'{{ addslashes($ex->name) }}\'?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 transition" title="Delete Exam">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-400">
                            <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p>No exams match the filter criteria.</p>
                            <a href="{{ route('exams.index') }}" class="text-purple-600 font-semibold text-xs mt-1 inline-block">Reset filters</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $exams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

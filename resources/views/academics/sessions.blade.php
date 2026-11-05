@extends('layouts.app')

@section('title', 'Academic Sessions - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Banner / Current Session Notification -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-xl p-5 text-white shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 flex items-center justify-center text-lg shrink-0">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white leading-tight">Switch the whole school to a new session?</h3>
                <p class="text-xs text-indigo-200 mt-1 max-w-xl">
                    Switching the active academic session changes the active enrollment context for attendance, grading, fee collections, and report cards across all school branches.
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <button onclick="document.getElementById('addSessionModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Add New Session</span>
            </button>
            <a href="{{ route('academics.promote-students') }}" class="px-3.5 py-2 text-xs font-semibold text-indigo-100 bg-white/10 hover:bg-white/20 rounded-lg border border-white/20 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-angles-up text-[10px]"></i>
                <span>Year-End Rollover</span>
            </a>
        </div>
    </div>

    <!-- Sessions List Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">All Academic Sessions</h3>
                <p class="text-xs text-slate-500">Master session timeline for curriculum and student records</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                {{ $sessions->count() }} Sessions Registered
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Session Name</th>
                        <th class="p-3.5">Period (Timeline)</th>
                        <th class="p-3.5 text-center">Students Enrolled</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $index => $sess)
                        <tr class="hover:bg-slate-50/80 transition {{ $sess->is_current ? 'bg-emerald-50/40' : '' }}">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-calendar-day {{ $sess->is_current ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                                    <span class="text-sm">{{ $sess->name }}</span>
                                    @if($sess->is_current)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            CURRENT
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3.5 text-slate-600">
                                <div class="flex items-center space-x-1.5 font-medium">
                                    <span>{{ date('d M Y', strtotime($sess->start_date)) }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span>{{ date('d M Y', strtotime($sess->end_date)) }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 text-center">
                                <span class="font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-full text-xs">
                                    {{ $sess->students_count }} students
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($sess->is_current)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Active Current
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Archived / Planned
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(!$sess->is_current)
                                        <form method="POST" action="{{ route('academics.sessions.set-current', $sess->id) }}" onsubmit="return confirm('Make {{ $sess->name }} the active session for the whole school?');" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition">
                                                Set Current
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-100/60 rounded border border-emerald-200 cursor-default">
                                            ✓ Active
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">
                                No academic sessions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add New Session -->
<div id="addSessionModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-regular fa-calendar-plus text-indigo-400"></i>
                <span>Add Academic Session</span>
            </h4>
            <button onclick="document.getElementById('addSessionModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('academics.sessions.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Session Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. 2027-2028 (27-28)" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Start Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" required value="{{ date('Y-04-01') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">End Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" required value="{{ date('Y-03-31', strtotime('+1 year')) }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <input type="checkbox" id="is_current" name="is_current" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_current" class="text-xs text-slate-700 font-medium">Set as Current Active Session for School</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addSessionModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Save Session
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'PTM Meetings & Lesson Planner')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">PTM Meetings & Lesson Planner</h1>
            </div>
            <p class="text-xs text-slate-500">Coordinate parent conferences and track weekly faculty syllabus coverage</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- Section 1: PTM Meetings Scheduled -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-handshake text-amber-500"></i>
                <span>Scheduled Parent-Teacher Meetings (PTM)</span>
            </h2>
            <span class="text-xs bg-amber-50 text-amber-800 font-bold px-2.5 py-0.5 rounded-full">{{ $ptmMeetings->count() }} Conferences</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($ptmMeetings as $ptm)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 text-sm">{{ $ptm->title }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">{{ $ptm->status }}</span>
                    </div>
                    <div class="text-slate-600 flex items-center space-x-4 text-[11px]">
                        <span><i class="fa-solid fa-calendar text-slate-400 mr-1"></i> {{ date('l, d M Y', strtotime($ptm->meeting_date)) }}</span>
                        <span><i class="fa-solid fa-clock text-slate-400 mr-1"></i> {{ date('h:i A', strtotime($ptm->start_time)) }} - {{ date('h:i A', strtotime($ptm->end_time)) }}</span>
                        <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $ptm->mode }}</span>
                    </div>
                    <p class="text-slate-500 text-[11px] pt-1 border-t border-slate-200/60">{{ $ptm->agenda }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 2: Weekly Lesson Plans & Syllabus Coverage -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-book-open text-blue-600"></i>
                <span>Weekly Lesson Plans & Syllabus Coverage</span>
            </h2>
            <span class="text-xs bg-blue-50 text-blue-800 font-bold px-2.5 py-0.5 rounded-full">Week 8 Curriculum</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Week</th>
                        <th class="py-2.5 px-3">Class & Section</th>
                        <th class="py-2.5 px-3">Subject</th>
                        <th class="py-2.5 px-3">Topic / Syllabus Unit</th>
                        <th class="py-2.5 px-3">Teaching Method</th>
                        <th class="py-2.5 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($lessonPlans as $lp)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-700">W-{{ $lp->week_number }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $lp->class_name }}</td>
                            <td class="py-2.5 px-3 text-slate-800">{{ $lp->subject_name }}</td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-900">{{ $lp->topic }}</div>
                                <div class="text-[10px] text-slate-400">{{ $lp->subtopics }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 text-[11px]">{{ $lp->teaching_method }}</td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                    {{ $lp->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection


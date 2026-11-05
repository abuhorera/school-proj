@extends('layouts.app')

@section('title', 'Promote Students Wizard - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('academics.partials.subnav')

    <!-- Wizard Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 rounded-xl p-5 text-white shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-1">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Year-End Rollover Wizard</span>
            </div>
            <h3 class="text-base font-bold text-white leading-tight">Batch Student Promotion & Progression</h3>
            <p class="text-xs text-emerald-100 mt-1 max-w-2xl">
                Roll forward student cohorts from current academic grade to the next academic session without losing historical fee ledgers, exam transcripts, or attendance logs.
            </p>
        </div>
        <div>
            <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 text-xs font-bold">
                ✓ Non-Destructive Progression
            </span>
        </div>
    </div>

    <!-- Step 1 & 2: Promotion Criteria Matrix -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('academics.promote-students') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- From (Source) -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <div class="flex items-center space-x-2 text-xs font-bold text-slate-800">
                        <span class="w-5 h-5 rounded-full bg-slate-700 text-white flex items-center justify-center text-[10px]">1</span>
                        <span>Promote FROM (Current Cohort)</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Source Session</label>
                            <select name="from_session_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-indigo-500">
                                @foreach($sessions as $sess)
                                    <option value="{{ $sess->id }}" {{ $fromSessionId == $sess->id ? 'selected' : '' }}>{{ $sess->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Source Class</label>
                            <select name="from_class_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-indigo-500">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ $fromClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Source Section</label>
                            <select name="from_section_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-indigo-500">
                                @foreach($fromSections as $s)
                                    <option value="{{ $s->id }}" {{ $fromSectionId == $s->id ? 'selected' : '' }}>Sec {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- To (Target) -->
                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-200 space-y-3">
                    <div class="flex items-center space-x-2 text-xs font-bold text-emerald-900">
                        <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">2</span>
                        <span>Promote TO (Target Grade)</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Session</label>
                            <select name="to_session_id" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-emerald-500">
                                @foreach($sessions as $sess)
                                    <option value="{{ $sess->id }}" {{ $toSessionId == $sess->id ? 'selected' : '' }}>{{ $sess->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Class</label>
                            <select name="to_class_id" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-emerald-500">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ $toClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Section</label>
                            <select name="to_section_id" class="w-full text-xs rounded-lg border border-slate-300 p-2 font-medium text-slate-700 focus:border-emerald-500">
                                <option value="">-- Same Section / A --</option>
                                @foreach($fromSections as $s)
                                    <option value="{{ $s->id }}" {{ $toSectionId == $s->id ? 'selected' : '' }}>Sec {{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Student Promotion List Form -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <form method="POST" action="{{ route('academics.promote-students.store') }}">
            @csrf
            <input type="hidden" name="from_session_id" value="{{ $fromSessionId }}">
            <input type="hidden" name="to_session_id" value="{{ $toSessionId }}">
            <input type="hidden" name="to_class_id" value="{{ $toClassId ?? ($fromClassId ? $classes->where('id', '>', $fromClassId)->first()->id ?? $fromClassId : 1) }}">
            <input type="hidden" name="to_section_id" value="{{ $toSectionId ?? $fromSectionId }}">

            <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-800">Eligible Candidates for Promotion</span>
                    <span class="text-xs text-slate-500">({{ $students->count() }} candidates found)</span>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="document.querySelectorAll('.promote-cb').forEach(c => c.checked = true)" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold">
                        Select All
                    </button>
                    <span class="text-slate-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('.promote-cb').forEach(c => c.checked = false)" class="text-xs text-slate-500 hover:text-slate-700 font-medium">
                        Deselect All
                    </button>
                    <button type="submit" onclick="return confirm('Promote selected candidates to the next academic level?');" class="px-4 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-angles-up text-[10px]"></i>
                        <span>Execute Promotion</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-100/70 text-slate-700 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-3.5 w-12 text-center">
                                <input type="checkbox" onchange="document.querySelectorAll('.promote-cb').forEach(c => c.checked = this.checked)" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="p-3.5">Admission No</th>
                            <th class="p-3.5">Roll No</th>
                            <th class="p-3.5">Student Name</th>
                            <th class="p-3.5 text-center">Academic Standing</th>
                            <th class="p-3.5 text-center">Recommended Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $st)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-3.5 text-center">
                                    <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" checked class="promote-cb rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </td>
                                <td class="p-3.5 font-mono font-medium text-slate-700">
                                    {{ $st->admission_no ?? 'ADM-' . $st->id }}
                                </td>
                                <td class="p-3.5 font-bold text-slate-800">
                                    #{{ $st->roll_no ?? $loop->iteration }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-800">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ substr($st->first_name, 0, 1) }}
                                        </div>
                                        <span>{{ $st->first_name }} {{ $st->last_name }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✓ Passed & Eligible
                                    </span>
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="text-xs font-semibold text-slate-700">
                                        Promote to Next Grade
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">
                                    No students available in this cohort for promotion.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Safety Information Banner -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-600">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                    <span>All previous attendance, exam scores, and fee records remain permanently linked to their student profile.</span>
                </div>
                <button type="submit" onclick="return confirm('Execute batch student promotion?');" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition">
                    Promote Batch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-award text-emerald-600"></i>
                <span>Student Behavior & Conduct Ledger</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Log merits, awards, house commendations and disciplinary warnings</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('modal-add-behavior').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Behavior Note</span>
            </button>
        </div>
    </div>

    <!-- 2 Stat Badges -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Merit Points</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">+{{ $positivePoints }} Points</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Awarded for sports, academic excellence, leadership</p>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-star"></i>
            </span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Disciplinary Flags</span>
                <div class="text-2xl font-black text-rose-600 mt-1">{{ $disciplinaryCount }} Flags</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Uniform, late arrival or classroom conduct cautions</p>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Conduct Log Entries ({{ $records->total() }})</span>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12">#</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Note / Incident Title</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4 text-center">Points</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Reported By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $idx => $rec)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            @if($rec->student)
                            <a href="{{ route('students.show', $rec->student->id) }}" class="hover:text-blue-600 hover:underline">
                                {{ $rec->student->first_name }} {{ $rec->student->last_name }}
                            </a>
                            <span class="text-[10px] text-slate-400 font-mono block">{{ $rec->student->admission_no }}</span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $rec->student->latestEnrollment->schoolClass->name ?? 'Class 10' }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            <div>{{ $rec->title }}</div>
                            @if($rec->description)
                            <div class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $rec->description }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            @if($rec->type == 'Positive' || $rec->type == 'Award')
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $rec->type }}
                            </span>
                            @else
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                {{ $rec->type }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center font-mono font-bold {{ $rec->points >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $rec->points >= 0 ? '+' : '' }}{{ $rec->points }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($rec->date)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $rec->reported_by ?? 'School Admin' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            No behavior records logged yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Behavior Record -->
<div id="modal-add-behavior" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-award text-emerald-600"></i>
                <span>Log Behavior Record</span>
            </h3>
            <button onclick="document.getElementById('modal-add-behavior').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('students.behavior.store') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Student Candidate <span class="text-red-500">*</span></label>
                <select name="student_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-emerald-500">
                    <option value="">Select Student</option>
                    @foreach($students as $st)
                    <option value="{{ $st->id }}">{{ $st->admission_no }} - {{ $st->first_name }} {{ $st->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Record Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-emerald-500">
                        <option value="Positive">Positive (Merit)</option>
                        <option value="Award">Award / Recognition</option>
                        <option value="Warning">Warning (Minor)</option>
                        <option value="Disciplinary">Disciplinary (Major)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Point Impact <span class="text-red-500">*</span></label>
                    <input type="number" name="points" value="5" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-emerald-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Title / Achievement / Reason <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. 1st Position in Debate Competition" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Incident Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Reported By</label>
                    <input type="text" name="reported_by" placeholder="e.g. Teacher / House Master" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-emerald-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Detailed Description</label>
                <textarea name="description" rows="2" placeholder="Provide event context or disciplinary remarks..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-emerald-500"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-add-behavior').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection


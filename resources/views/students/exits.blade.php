@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-file-invoice text-amber-600"></i>
                <span>TC & Student Exit Management</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage formal withdrawal, Transfer Certificate (TC) dispatches, and school leaving clearances</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('modal-issue-tc').classList.remove('hidden')" class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-file-signature text-xs"></i>
                <span>Issue Transfer Certificate (TC)</span>
            </button>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Issued Transfer Certificates ({{ $exits->total() }})</span>
            <span class="text-[11px] text-slate-400">Withdrawal clears student from active muster roll</span>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">TC Serial No</th>
                        <th class="py-3 px-4">Adm No.</th>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Class at Exit</th>
                        <th class="py-3 px-4">Reason for Withdrawal</th>
                        <th class="py-3 px-4">Certificate Printed</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exits as $ex)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-amber-700">
                            {{ $ex->tc_number }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-700">
                            {{ $ex->student->admission_no ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            @if($ex->student)
                            <a href="{{ route('students.show', $ex->student->id) }}" class="hover:text-blue-600 hover:underline">
                                {{ $ex->student->first_name }} {{ $ex->student->last_name }}
                            </a>
                            @else
                            <span class="text-slate-400">Archived Student</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $ex->student->latestEnrollment->schoolClass->name ?? 'Class 10' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-700">
                            <div>{{ $ex->reason }}</div>
                            @if($ex->remarks)
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $ex->remarks }}</div>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($ex->certificate_printed ?? $ex->exit_date)->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                {{ $ex->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            No student exit records or Transfer Certificates issued yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Issue TC -->
<div id="modal-issue-tc" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-file-invoice text-amber-600"></i>
                <span>Issue Transfer Certificate (TC)</span>
            </h3>
            <button onclick="document.getElementById('modal-issue-tc').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('students.exits.store') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Select Candidate <span class="text-red-500">*</span></label>
                <select name="student_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-amber-500">
                    <option value="">Select Enrolled Student</option>
                    @foreach($students as $st)
                    <option value="{{ $st->id }}">{{ $st->admission_no }} - {{ $st->first_name }} {{ $st->last_name }} ({{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Reason for Withdrawal <span class="text-red-500">*</span></label>
                <select name="reason" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-amber-500">
                    <option value="Parent Relocation (Transfer)">Parent Relocation (Transfer)</option>
                    <option value="Completed Highest Class (Graduation)">Completed Highest Class (Graduation)</option>
                    <option value="Admission to Specialized Institute">Admission to Specialized Institute</option>
                    <option value="Personal / Domestic Grounds">Personal / Domestic Grounds</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Effective Exit Date <span class="text-red-500">*</span></label>
                <input type="date" name="exit_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-amber-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Clearance Remarks / Conduct</label>
                <textarea name="remarks" rows="2" placeholder="e.g. Conduct Good. Dues cleared. Promoted to Class 11..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-amber-500"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-issue-tc').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Issue & Record TC</button>
            </div>
        </form>
    </div>
</div>
@endsection


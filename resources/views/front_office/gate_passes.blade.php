@extends('layouts.app')

@section('title', 'Gate Passes - MultiSchool ERP')

@section('content')
<div class="space-y-6">
    @include('front_office.partials.subnav')

    <!-- Top Action & Filter Strip -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('front-office.gate-passes') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($statusFilter) || $statusFilter == 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Passes
            </a>
            <a href="{{ route('front-office.gate-passes', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $statusFilter == 'approved' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Approved
            </a>
            <a href="{{ route('front-office.gate-passes', ['status' => 'out']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $statusFilter == 'out' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Currently Out
            </a>
            <a href="{{ route('front-office.gate-passes', ['status' => 'returned']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $statusFilter == 'returned' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Returned
            </a>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('front-office.gate-terminal') }}" class="px-3.5 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-barcode text-emerald-600"></i>
                <span>Open Gate Terminal</span>
            </a>
            <button onclick="document.getElementById('addGatePassModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Issue Gate Pass</span>
            </button>
        </div>
    </div>

    <!-- Gate Passes Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-id-badge text-indigo-600"></i>
                <span class="text-xs font-bold text-slate-800">Authorized Gate Passes Register ({{ $passes->count() }})</span>
            </div>
            <span class="text-xs text-slate-400">Strict student departure security tracking</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5 w-12 text-center">#</th>
                        <th class="p-3.5">Pass Number</th>
                        <th class="p-3.5">Student Name</th>
                        <th class="p-3.5">Escort / Guardian</th>
                        <th class="p-3.5">Reason & Purpose</th>
                        <th class="p-3.5">Out Time</th>
                        <th class="p-3.5">Gate</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($passes as $index => $gp)
                        <tr class="hover:bg-slate-50/80 transition {{ $gp->status == 'out' ? 'bg-amber-50/40' : '' }}">
                            <td class="p-3.5 text-center font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3.5 font-mono font-bold text-indigo-600">
                                {{ $gp->pass_number }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr($gp->visitor_name, 0, 1) }}
                                    </div>
                                    <span>{{ $gp->visitor_name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5 font-medium text-slate-700">
                                <div>{{ $gp->escort_name }} ({{ $gp->escort_relation ?? 'Guardian' }})</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $gp->escort_phone }}</div>
                            </td>
                            <td class="p-3.5 max-w-xs">
                                <div class="font-semibold text-slate-800">{{ $gp->purpose }}</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ $gp->reason_text }}</div>
                            </td>
                            <td class="p-3.5 text-slate-600 font-medium">
                                {{ $gp->out_time ? date('h:i A', strtotime($gp->out_time)) : 'Pending Departure' }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 border border-slate-200">
                                    {{ $gp->gate_name ?? 'Main Gate' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($gp->status == 'out')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1 animate-pulse"></span>
                                        Checked Out
                                    </span>
                                @elseif($gp->status == 'returned')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Returned
                                    </span>
                                @elseif($gp->status == 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        Approved (At Gate)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($gp->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    @if($gp->status == 'approved')
                                        <form method="POST" action="{{ route('front-office.gate-passes.status', $gp->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="out">
                                            <button type="submit" class="px-2 py-1 text-[10px] font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 rounded transition">
                                                Check Out
                                            </button>
                                        </form>
                                    @elseif($gp->status == 'out')
                                        <form method="POST" action="{{ route('front-office.gate-passes.status', $gp->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="returned">
                                            <button type="submit" class="px-2 py-1 text-[10px] font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 rounded transition">
                                                Check In
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('front-office.gate-passes.delete', $gp->id) }}" onsubmit="return confirm('Delete gate pass {{ $gp->pass_number }}?');" class="inline">
                                        @csrf
                                        <button type="submit" title="Delete Pass" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-400">
                                No gate passes issued matching filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Issue Gate Pass -->
<div id="addGatePassModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
            <h4 class="text-sm font-bold flex items-center space-x-2">
                <i class="fa-solid fa-id-badge text-indigo-400"></i>
                <span>Issue Student Gate Pass</span>
            </h4>
            <button onclick="document.getElementById('addGatePassModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('front-office.gate-passes.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select Student <span class="text-rose-500">*</span></label>
                <select name="student_id" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} (Adm #{{ $st->admission_no ?? $st->id }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Leaving Reason <span class="text-rose-500">*</span></label>
                    <select name="reason_id" required class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="Medical Emergency / Clinic">Medical Emergency / Clinic</option>
                        <option value="Early Family Dismissal">Early Family Dismissal</option>
                        <option value="Inter-School Competition">Inter-School Competition</option>
                        <option value="Official School Representation">Official Representation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Departure Gate</label>
                    <select name="gate_name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="Main Gate 1">Main Gate 1</option>
                        <option value="Bus Gate 2">Bus Gate 2</option>
                        <option value="Sports Complex Gate">Sports Complex Gate</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Escort Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="escort_name" required placeholder="Person picking up" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Relation <span class="text-rose-500">*</span></label>
                    <input type="text" name="escort_relation" required placeholder="e.g. Father, Mother" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Escort Mobile <span class="text-rose-500">*</span></label>
                    <input type="text" name="escort_phone" required placeholder="Mobile #" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Medical / Departure Reason Note</label>
                <input type="text" name="reason_text" placeholder="e.g. High fever reported at clinic, advised rest" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center space-x-2 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <input type="checkbox" id="is_return_expected" name="is_return_expected" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_return_expected" class="text-xs text-slate-700 font-medium">Expect student to return to campus later today</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addGatePassModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-xs transition">
                    Issue Authorized Pass
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


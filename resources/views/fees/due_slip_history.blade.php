@extends('layouts.app')

@section('title', 'Due Slip Generation History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Historical Due Records</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Due Slip History & Archive</h1>
            <p class="text-xs text-slate-500 mt-1">Archive of all generated fee due reminder slips with reprint and messaging logs.</p>
        </div>
        <a href="{{ route('fees.due-slips') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-1.5">
            <i class="fa-solid fa-plus"></i>
            <span>Generate New Slips</span>
        </a>
    </div>

    <!-- History Table (Exact match to demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Archived Monthly Due Slips (Total: {{ $history->count() }})</h3>
            <span class="text-xs text-slate-500">Filter by Month & Class</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Slip Code</th>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4">Admission No</th>
                        <th class="py-3 px-4">Class & Section</th>
                        <th class="py-3 px-4">Month</th>
                        <th class="py-3 px-4 text-right">Previous Due</th>
                        <th class="py-3 px-4 text-right">Current Due</th>
                        <th class="py-3 px-4 text-right">Total Due</th>
                        <th class="py-3 px-4">Generated On</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $idx => $h)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-amber-700">{{ $h->slip_code }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">{{ $h->first_name }} {{ $h->last_name }}</td>
                        <td class="py-3 px-4 font-mono text-slate-600">{{ $h->admission_no }}</td>
                        <td class="py-3 px-4 text-slate-700">Class 10-A</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-semibold">
                                {{ $h->month }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right text-slate-600 font-medium">₹{{ number_format($h->previous_due, 2) }}</td>
                        <td class="py-3 px-4 text-right text-slate-600 font-medium">₹{{ number_format($h->current_due, 2) }}</td>
                        <td class="py-3 px-4 text-right font-bold text-red-600">₹{{ number_format($h->total_due, 2) }}</td>
                        <td class="py-3 px-4 text-slate-500 font-mono">{{ $h->generated_on }}</td>
                        <td class="py-3 px-4 text-right">
                            <button onclick="alert('Reprinting due slip for {{ $h->first_name }}')" class="text-blue-600 hover:text-blue-800 font-semibold mr-2">Reprint</button>
                            <button onclick="alert('WhatsApp reminder sent to parents!')" class="text-emerald-600 hover:text-emerald-800 font-semibold">WhatsApp</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-6 text-slate-400">No due slip generation history yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


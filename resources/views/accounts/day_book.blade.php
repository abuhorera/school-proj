@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-book-journal-whills text-indigo-600"></i>
                <span>Institutional Day Book</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Chronological double-entry cash/bank ledger showing all debits and credits for the day</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-3 py-1.5 rounded-xl shadow-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Print Day Book</span>
            </button>
        </div>
    </div>

    <!-- Date Picker Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form method="GET" action="{{ route('accounts.day-book') }}" class="flex items-center space-x-3 text-xs">
            <label class="font-bold text-slate-700">Select Date:</label>
            <input type="date" name="date" value="{{ $date }}" class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-indigo-500 font-medium">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-lg shadow-xs transition">
                Load Day
            </button>
        </form>
        <div class="flex items-center space-x-4 text-xs">
            <span class="text-slate-500">Day: <strong class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</strong></span>
            <span class="text-slate-300">|</span>
            <span class="text-slate-500">Total Entries: <strong class="text-slate-800 font-bold">{{ $entries->count() }}</strong></span>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-28">Date</th>
                        <th class="py-3 px-4 w-32">Voucher</th>
                        <th class="py-3 px-4 w-32">Type</th>
                        <th class="py-3 px-4">Ledger</th>
                        <th class="py-3 px-4">Narration</th>
                        <th class="py-3 px-4 text-right w-28 text-rose-600">Debit (Dr)</th>
                        <th class="py-3 px-4 text-right w-28 text-emerald-600">Credit (Cr)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($entries as $e)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 text-slate-500 font-mono whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                            {{ $e->voucher }}
                        </td>
                        <td class="py-3 px-4">
                            @if(str_contains($e->type, 'Receipt'))
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $e->type }}
                            </span>
                            @else
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                {{ $e->type }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $e->ledger }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            {{ $e->narration }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-rose-600">
                            {{ $e->debit > 0 ? '₹' . number_format($e->debit, 2) : '-' }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600">
                            {{ $e->credit > 0 ? '₹' . number_format($e->credit, 2) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-2xl mb-2 text-slate-300 block"></i>
                            <div>No entries for this period. Try a different date or record a new voucher.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <!-- Day Total Footer -->
                @if($entries->count() > 0)
                <tfoot class="bg-slate-50 font-bold border-t-2 border-slate-200 text-xs text-slate-800">
                    <tr>
                        <td colspan="5" class="py-3 px-4 text-right uppercase text-[10px] tracking-wider text-slate-500">
                            Day Closing Total:
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-rose-600">
                            ₹{{ number_format($totalDebit, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-emerald-600">
                            ₹{{ number_format($totalCredit, 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection


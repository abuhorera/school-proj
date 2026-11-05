@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-arrow-down-left text-emerald-600"></i>
                <span>Income Records</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive audit ledger of all non-fee and institutional revenue receipts</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('accounts.incomes.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add New Income</span>
            </a>
            <a href="{{ route('accounts.income-heads.index') }}" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition">
                <span>Manage Heads</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('accounts.incomes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Income Head</label>
                <select name="income_head_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-indigo-500">
                    <option value="">All Income Heads</option>
                    @foreach($heads as $h)
                    <option value="{{ $h->id }}" {{ request('income_head_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-indigo-500">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-indigo-500">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-1.5 px-3 rounded-lg transition flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('accounts.incomes.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Income Entries ({{ $incomes->total() }})</span>
            <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                Filtered Total: ₹{{ number_format($totalAmount, 2) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Name / Particulars</th>
                        <th class="py-3 px-4">Income Head</th>
                        <th class="py-3 px-4">Invoice / Voucher</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Account / Mode</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomes as $idx => $inc)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">
                            <div>{{ $inc->name }}</div>
                            @if($inc->description)
                            <div class="text-[10px] text-slate-400 font-normal mt-0.5">{{ Str::limit($inc->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $inc->incomeHead->name ?? 'Income' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600 font-semibold">
                            {{ $inc->invoice_number ?? $inc->invoice_no ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($inc->date)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-4 text-slate-600">
                            @if($inc->bankAccount)
                            <span class="font-medium text-slate-700"><i class="fa-solid fa-building-columns text-[10px] text-slate-400 mr-1"></i>{{ $inc->bankAccount->bank_name }}</span>
                            @else
                            <span class="text-slate-500"><i class="fa-solid fa-money-bill text-[10px] text-slate-400 mr-1"></i>Cash Hand</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-extrabold text-emerald-600 whitespace-nowrap text-sm">
                            ₹{{ number_format($inc->amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <form action="{{ route('accounts.incomes.destroy', $inc->id) }}" method="POST" onsubmit="return confirm('Delete this income record?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition" title="Delete">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            No income records found for this filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($incomes->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $incomes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


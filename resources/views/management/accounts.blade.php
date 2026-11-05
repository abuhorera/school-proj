@extends('layouts.app')

@section('title', 'Institutional Accounts & Bank Books')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Management /</span>
                <h1 class="text-xl font-bold text-slate-900">Institutional Accounts & Bank Books</h1>
            </div>
            <p class="text-xs text-slate-500">Track institutional cash flows, bank ledger balances, income heads, and expense categories</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('management.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Management Hub
            </a>
        </div>
    </div>

    <!-- Bank Accounts Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($bankAccounts as $ba)
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-5 rounded-2xl border border-slate-700 shadow-md flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ $ba->bank_name }} • {{ $ba->branch }}</span>
                    <h3 class="text-sm font-bold text-white mt-0.5">{{ $ba->account_name }}</h3>
                    <div class="font-mono text-xs text-slate-400 mt-1">A/C: {{ $ba->account_number }} • IFSC: {{ $ba->ifsc_code }}</div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase text-emerald-400 font-bold block">Current Balance</span>
                    <span class="text-2xl font-black text-emerald-400">₹{{ number_format($ba->current_balance, 2) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Income Ledger Table -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i>
                <span>Institutional Income Ledger</span>
            </h2>
            <span class="text-xs bg-emerald-50 text-emerald-700 font-bold px-2.5 py-0.5 rounded-full">
                Total: ₹{{ number_format($totalIncome, 0) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Invoice No</th>
                        <th class="py-2.5 px-3">Income Head</th>
                        <th class="py-2.5 px-3">Description / Name</th>
                        <th class="py-2.5 px-3">Payment Method</th>
                        <th class="py-2.5 px-3 text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomes as $inc)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-2.5 px-3 font-semibold text-slate-700">{{ date('d M Y', strtotime($inc->date)) }}</td>
                            <td class="py-2.5 px-3 font-mono font-bold text-blue-600">{{ $inc->invoice_no }}</td>
                            <td class="py-2.5 px-3"><span class="bg-emerald-50 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">{{ $inc->head_name }}</span></td>
                            <td class="py-2.5 px-3 text-slate-800 font-medium">{{ $inc->name }}</td>
                            <td class="py-2.5 px-3 text-slate-600">{{ $inc->payment_method }}</td>
                            <td class="py-2.5 px-3 text-right font-black text-emerald-600">₹{{ number_format($inc->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-center text-slate-400 italic">No income entries recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Income & Expense Heads Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Income Heads -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center space-x-2">
                <i class="fa-solid fa-plus-circle text-emerald-500"></i>
                <span>Configured Income Heads</span>
            </h3>
            <div class="space-y-2 text-xs">
                @foreach($incomeHeads as $ih)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800">{{ $ih->name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $ih->description }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Active</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Expense Heads -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700 flex items-center space-x-2">
                <i class="fa-solid fa-minus-circle text-rose-500"></i>
                <span>Configured Expense Heads</span>
            </h3>
            <div class="space-y-2 text-xs">
                @foreach($expenseHeads as $eh)
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800">{{ $eh->name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $eh->description }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">Category</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection


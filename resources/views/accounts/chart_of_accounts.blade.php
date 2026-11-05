@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-sitemap text-blue-600"></i>
                <span>Institutional Chart of Accounts (COA)</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Hierarchical taxonomy of assets, liabilities, revenues, and expenditures</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('accounts.income-heads.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3 py-1.5 rounded-xl shadow-xs transition">
                + Income Head
            </a>
            <a href="{{ route('accounts.expense-heads.create') }}" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-3 py-1.5 rounded-xl shadow-xs transition">
                + Expense Head
            </a>
        </div>
    </div>

    <!-- 4 Pillars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
        <!-- 1. ASSETS -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">1</span>
                    <div>
                        <h3 class="font-bold text-sm text-slate-800">1000 - Assets</h3>
                        <p class="text-[11px] text-slate-400">Current liquid assets and cash reserves</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 uppercase">Debit Nature</span>
            </div>

            <div class="space-y-2 pl-3 border-l-2 border-purple-200">
                <div class="font-bold text-slate-700">1100 - Bank Accounts</div>
                <div class="space-y-1.5 pl-3">
                    @foreach($bankAccounts as $acc)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>{{ $acc->bank_name }} - {{ $acc->account_name }} (•••• {{ substr($acc->account_number, -4) }})</span>
                        <strong class="font-mono text-slate-900">₹{{ number_format($acc->current_balance, 2) }}</strong>
                    </div>
                    @endforeach
                </div>

                <div class="font-bold text-slate-700 pt-2">1200 - Cash In Hand</div>
                <div class="space-y-1.5 pl-3">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>Campus Reception Counter Safe</span>
                        <strong class="font-mono text-slate-900">₹45,000.00</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. LIABILITIES -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">2</span>
                    <div>
                        <h3 class="font-bold text-sm text-slate-800">2000 - Liabilities</h3>
                        <p class="text-[11px] text-slate-400">Caution deposits, advance fees & payables</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 uppercase">Credit Nature</span>
            </div>

            <div class="space-y-2 pl-3 border-l-2 border-amber-200">
                <div class="font-bold text-slate-700">2100 - Student Caution Deposits</div>
                <div class="space-y-1.5 pl-3">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>Refundable Security Deposit Vault</span>
                        <strong class="font-mono text-slate-900">₹3,40,000.00</strong>
                    </div>
                </div>

                <div class="font-bold text-slate-700 pt-2">2200 - Current Payables</div>
                <div class="space-y-1.5 pl-3">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>EPF & Staff TDS Provision (Month Oct)</span>
                        <strong class="font-mono text-slate-900">₹82,500.00</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. INCOMES -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">3</span>
                    <div>
                        <h3 class="font-bold text-sm text-slate-800">3000 - Revenues & Incomes</h3>
                        <p class="text-[11px] text-slate-400">Tuition fees and auxiliary institutional receipts</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Credit Nature</span>
            </div>

            <div class="space-y-2 pl-3 border-l-2 border-emerald-200">
                <div class="font-bold text-slate-700">3100 - Academic Tuition Fee Heads</div>
                <div class="space-y-1.5 pl-3">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>Student Tuition & Exam Collections</span>
                        <span class="text-[10px] font-bold text-emerald-600">Active</span>
                    </div>
                </div>

                <div class="font-bold text-slate-700 pt-2">3200 - Auxiliary Income Heads</div>
                <div class="space-y-1.5 pl-3">
                    @foreach($incomeHeads as $ih)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>{{ $ih->name }}</span>
                        <span class="text-[10px] font-bold text-slate-500">{{ $ih->incomes_count }} vouchers</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. EXPENSES -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">4</span>
                    <div>
                        <h3 class="font-bold text-sm text-slate-800">4000 - Expenditures</h3>
                        <p class="text-[11px] text-slate-400">Operating overheads, utilities and maintenance</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 uppercase">Debit Nature</span>
            </div>

            <div class="space-y-2 pl-3 border-l-2 border-rose-200">
                <div class="font-bold text-slate-700">4100 - Operational & Administrative Heads</div>
                <div class="space-y-1.5 pl-3">
                    @foreach($expenseHeads as $eh)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-100">
                        <span>{{ $eh->name }}</span>
                        <span class="text-[10px] font-bold text-slate-500">{{ $eh->expenses_count }} vouchers</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Secondary Nav -->
    @include('accounts.partials.subnav')

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center space-x-2.5">
                <i class="fa-solid fa-calculator text-emerald-600"></i>
                <span>Accounts & Bookkeeping</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Track income, expenses, ledgers and hand audit-ready books to your accountant.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('accounts.day-book') }}" class="bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-bold px-3 py-2 rounded-xl flex items-center space-x-1.5 transition">
                <i class="fa-solid fa-book text-xs"></i>
                <span>View Day Book</span>
            </a>
            <a href="{{ route('accounts.bank-accounts.index') }}" class="bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 text-xs font-bold px-3 py-2 rounded-xl flex items-center space-x-1.5 transition">
                <i class="fa-solid fa-building-columns text-xs"></i>
                <span>Manage Banks</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Financial KPI Stat Cards (Exact match to demo) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Today's Collection -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Today's Collection</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calendar-day"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-slate-800">₹{{ number_format($todayCollection, 2) }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Fee receipts + daily cash collection</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Live Today Status</span>
                <span class="font-bold text-emerald-600 flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1"></span> Real-time</span>
            </div>
        </div>

        <!-- 2. This Month Income -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">This Month Income</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-blue-600">₹{{ number_format($monthIncome, 2) }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Fees (₹{{ number_format($monthFees, 0) }}) + Other (₹{{ number_format($monthOtherIncome, 0) }})</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Total Inflow</span>
                <a href="{{ route('accounts.incomes.index') }}" class="font-bold text-blue-600 hover:underline">View Ledger &rarr;</a>
            </div>
        </div>

        <!-- 3. This Month Expense -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">This Month Expense</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-rose-600">₹{{ number_format($monthExpense, 2) }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Recorded vendor & operational payments</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Outflow Entries</span>
                <a href="{{ route('accounts.expenses.index') }}" class="font-bold text-rose-600 hover:underline">View Expenses &rarr;</a>
            </div>
        </div>

        <!-- 4. Cash & Bank Live Balance -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cash & Bank Balance</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-vault"></i>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-purple-700">₹{{ number_format($totalCashAndBank, 2) }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-0.5">Live consolidated bank accounts & cash in hand</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Active Accounts</span>
                <span class="font-bold text-purple-600">{{ $bankAccounts->count() }} Banks Connected</span>
            </div>
        </div>
    </div>

    <!-- Middle Section: Chart & Live Bank Accounts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 12-Month Inflow vs Outflow Visualizer -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Income vs Expense (Last 12 Months)</h3>
                    <p class="text-xs text-slate-400">Monthly fiscal comparison between collections and expenditures</p>
                </div>
                <div class="flex items-center space-x-3 text-xs font-semibold">
                    <span class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-slate-600">Income</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span><span class="text-slate-600">Expense</span></span>
                </div>
            </div>

            <!-- Simulated Responsive Bars -->
            <div class="h-56 flex items-end justify-between gap-3 pt-6 pb-2 px-2 border-b border-slate-100">
                @php
                    $months = ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'];
                    $incBars = [45, 60, 75, 95, 80, 70, 85, 90, 65, 88, 92, 78];
                    $expBars = [30, 42, 38, 55, 48, 52, 60, 58, 45, 50, 48, 35];
                @endphp
                @foreach($months as $idx => $m)
                <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end group">
                    <div class="w-full flex items-end justify-center gap-1 h-full">
                        <div style="height: {{ $incBars[$idx] }}%;" class="w-3 sm:w-4 bg-emerald-500 rounded-t-sm transition-all duration-300 group-hover:bg-emerald-600 relative">
                            <span class="opacity-0 group-hover:opacity-100 absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] px-1.5 py-0.5 rounded pointer-events-none transition whitespace-nowrap z-20">₹{{ $incBars[$idx] }}k</span>
                        </div>
                        <div style="height: {{ $expBars[$idx] }}%;" class="w-3 sm:w-4 bg-rose-400 rounded-t-sm transition-all duration-300 group-hover:bg-rose-500 relative">
                            <span class="opacity-0 group-hover:opacity-100 absolute -top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] px-1.5 py-0.5 rounded pointer-events-none transition whitespace-nowrap z-20">₹{{ $expBars[$idx] }}k</span>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase">{{ $m }}</span>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-3 gap-2 pt-2 text-center text-xs">
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Annual Inflow</span>
                    <div class="font-extrabold text-slate-800 text-sm mt-0.5">₹8,92,000</div>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Annual Outflow</span>
                    <div class="font-extrabold text-slate-800 text-sm mt-0.5">₹5,41,000</div>
                </div>
                <div class="bg-emerald-50 p-2.5 rounded-xl border border-emerald-100">
                    <span class="text-[10px] text-emerald-600 uppercase font-bold">Net Operating Surplus</span>
                    <div class="font-extrabold text-emerald-700 text-sm mt-0.5">+₹3,51,000</div>
                </div>
            </div>
        </div>

        <!-- Bank Accounts Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Connected Bank Accounts</h3>
                    <p class="text-xs text-slate-400">Institutional accounts & escrow</p>
                </div>
                <a href="{{ route('accounts.bank-accounts.index') }}" class="text-xs text-indigo-600 hover:underline font-bold">+ Manage</a>
            </div>

            <div class="space-y-3">
                @foreach($bankAccounts as $acc)
                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 transition flex items-center justify-between">
                    <div class="space-y-0.5">
                        <div class="font-bold text-xs text-slate-800 flex items-center space-x-1.5">
                            <i class="fa-solid fa-building-columns text-slate-400 text-xs"></i>
                            <span>{{ $acc->bank_name }}</span>
                            <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded bg-slate-200 text-slate-600 uppercase">{{ $acc->account_type }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono">A/C: •••• {{ substr($acc->account_number, -4) }}</div>
                        <div class="text-[10px] text-slate-400">{{ $acc->branch ?? 'Main Branch' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-extrabold text-sm text-slate-800">₹{{ number_format($acc->current_balance, 2) }}</div>
                        <span class="text-[10px] font-bold text-emerald-600">Active</span>
                    </div>
                </div>
                @endforeach

                <!-- Petty Cash in Hand -->
                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <div class="space-y-0.5">
                        <div class="font-bold text-xs text-slate-800 flex items-center space-x-1.5">
                            <i class="fa-solid fa-money-bill-1 text-slate-400 text-xs"></i>
                            <span>School Petty Cash Chest</span>
                        </div>
                        <div class="text-[11px] text-slate-500">Campus Counter Safe</div>
                    </div>
                    <div class="text-right">
                        <div class="font-extrabold text-sm text-slate-800">₹45,000.00</div>
                        <span class="text-[10px] font-bold text-emerald-600">Verified</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Consolidated Liquidity:</span>
                <strong class="text-purple-700 font-extrabold text-sm">₹{{ number_format($totalCashAndBank, 2) }}</strong>
            </div>
        </div>
    </div>

    <!-- Recent Vouchers Ledger Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-sm text-slate-800">Recent Accounting Vouchers</h3>
                <p class="text-xs text-slate-400">Chronological feed of fee receipts, other revenues, and vendor payment vouchers</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('accounts.incomes.create') }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition flex items-center space-x-1">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>+ Add Income</span>
                </a>
                <a href="{{ route('accounts.expenses.create') }}" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition flex items-center space-x-1">
                    <i class="fa-solid fa-minus text-[10px]"></i>
                    <span>+ Add Expense</span>
                </a>
                <a href="{{ route('accounts.day-book') }}" class="px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-xs font-bold transition">
                    Day Book &rarr;
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Voucher No</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Particulars / Details</th>
                        <th class="py-3 px-4">Accounting Head</th>
                        <th class="py-3 px-4">Voucher Type</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentVouchers as $v)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">
                            {{ $v->voucher }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                            {{ $v->date }}
                        </td>
                        <td class="py-3 px-4 text-slate-700 font-medium">
                            {{ $v->particular }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $v->head }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($v->type == 'Fee Receipt')
                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fa-solid fa-graduation-cap text-[9px]"></i>
                                    <span>Fee Receipt</span>
                                </span>
                            @elseif($v->type == 'Income')
                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-arrow-down text-[9px]"></i>
                                    <span>Income Receipt</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa-solid fa-arrow-up text-[9px]"></i>
                                    <span>Payment Voucher</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-extrabold whitespace-nowrap {{ $v->is_debit ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $v->is_debit ? '-' : '+' }}₹{{ number_format($v->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            No accounting vouchers recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


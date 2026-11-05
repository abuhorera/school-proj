@extends('layouts.app')

@section('title', 'Staff Loans - Human Resource')

@section('content')
<div class="space-y-6">
    @include('staff.partials.subnav')

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Staff Loan & Advance Management</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Track employee salary advances, institutional emergency loans, and automated payroll EMI deductions</p>
        </div>
        <div>
            <button onclick="document.getElementById('applyLoanModal').classList.remove('hidden')" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-plus"></i>
                <span>Issue New Loan</span>
            </button>
        </div>
    </div>

    <!-- Loan KPI Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Disbursed</p>
                <h3 class="text-xl font-black text-slate-900 mt-1 font-mono">₹{{ number_format($loans->sum('loan_amount'), 2) }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $loans->count() }} total loans issued</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Repaid To Date</p>
                <h3 class="text-xl font-black text-emerald-600 mt-1 font-mono">₹{{ number_format($loans->sum('amount_repaid'), 2) }}</h3>
                <p class="text-[11px] text-emerald-600 mt-0.5">Recovered installments</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Outstanding Principal</p>
                <h3 class="text-xl font-black text-rose-600 mt-1 font-mono">₹{{ number_format($loans->sum('remaining_balance'), 2) }}</h3>
                <p class="text-[11px] text-rose-500 mt-0.5">Pending collection</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-scale-unbalanced"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Borrowers</p>
                <h3 class="text-xl font-black text-slate-900 mt-1 font-mono">{{ $loans->where('status', '!=', 'Repaid')->count() }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Staff with open balances</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
        </div>
    </div>

    <!-- Loans Ledger Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Staff Loan Ledger & Repayment Status</h3>
            <span class="text-xs text-slate-400 font-mono">{{ $loans->count() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Staff Member</th>
                        <th class="py-3 px-4">Loan Amount</th>
                        <th class="py-3 px-4">Monthly EMI</th>
                        <th class="py-3 px-4">Amount Repaid</th>
                        <th class="py-3 px-4">Remaining Balance</th>
                        <th class="py-3 px-4">Disbursed Date</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Repayment Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($loans as $index => $l)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($l->staff->first_name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $l->staff->first_name ?? 'Staff' }} {{ $l->staff->last_name ?? '' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $l->staff->employee_id ?? 'N/A' }} • {{ $l->staff->department ?? 'General' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-900">
                            ₹{{ number_format($l->loan_amount, 2) }}
                            <div class="text-[10px] text-slate-400 font-normal">Term: {{ $l->term_months }} Mos</div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-purple-700">
                            ₹{{ number_format($l->monthly_emi, 2) }}
                        </td>
                        <td class="py-3 px-4 font-mono font-semibold text-emerald-600">
                            ₹{{ number_format($l->amount_repaid, 2) }}
                        </td>
                        <td class="py-3 px-4 font-mono font-bold {{ $l->remaining_balance > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            ₹{{ number_format($l->remaining_balance, 2) }}
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">
                            {{ $l->disbursed_date ? \Carbon\Carbon::parse($l->disbursed_date)->format('d M Y') : 'Pending' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($l->status == 'Repaid')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[9px] mr-0.5"></i> Repaid
                                </span>
                            @elseif($l->status == 'Disbursed' || $l->status == 'Approved')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    <i class="fa-solid fa-clock-rotate-left text-[9px] mr-0.5"></i> Active
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $l->status }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($l->remaining_balance > 0)
                            <form action="{{ route('staff.loans.repay', $l->id) }}" method="POST" class="inline-flex items-center space-x-1.5">
                                @csrf
                                <input type="hidden" name="amount" value="{{ min($l->monthly_emi, $l->remaining_balance) }}">
                                <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold transition shadow-xs flex items-center space-x-1" title="Record 1 Monthly EMI Payment">
                                    <i class="fa-solid fa-credit-card text-[10px]"></i>
                                    <span>Pay EMI (₹{{ number_format(min($l->monthly_emi, $l->remaining_balance), 0) }})</span>
                                </button>
                            </form>
                            @else
                            <span class="text-[11px] text-slate-400 font-semibold italic">Cleared</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">No staff loans or advances recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Apply / Issue New Loan -->
<div id="applyLoanModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-lg w-full overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Issue Staff Advance / Loan</h3>
            <button onclick="document.getElementById('applyLoanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('staff.loans.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Staff Member <span class="text-rose-500">*</span></label>
                <select name="staff_id" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    <option value="">-- Select Staff Member --</option>
                    @foreach($staffList as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->employee_id }} - {{ $st->department }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Loan Amount (₹) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="1000" name="loan_amount" id="loan_amount" required placeholder="50000" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 font-mono focus:ring-2 focus:ring-pink-500" oninput="calculateEmi()">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tenure (Months) <span class="text-rose-500">*</span></label>
                    <select name="term_months" id="term_months" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 font-mono focus:ring-2 focus:ring-pink-500" onchange="calculateEmi()">
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="10" selected>10 Months</option>
                        <option value="12">12 Months (1 Year)</option>
                        <option value="18">18 Months</option>
                        <option value="24">24 Months (2 Years)</option>
                    </select>
                </div>
            </div>

            <div class="p-3 bg-pink-50/60 rounded-xl border border-pink-100 flex items-center justify-between text-xs">
                <span class="text-slate-600 font-semibold">Estimated Monthly EMI Deduction:</span>
                <span class="font-mono font-black text-pink-700 text-sm" id="calcEmiDisplay">₹5,000.00</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Purpose / Justification</label>
                <textarea name="reason" rows="2.5" placeholder="Reason for salary advance or emergency institutional loan..." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('applyLoanModal').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold shadow-xs">Disburse Loan</button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateEmi() {
    const amount = parseFloat(document.getElementById('loan_amount').value) || 0;
    const months = parseInt(document.getElementById('term_months').value) || 1;
    const emi = amount > 0 ? (amount / months).toFixed(2) : '0.00';
    document.getElementById('calcEmiDisplay').textContent = '₹' + Number(emi).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
@endsection


@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Accounting Subnav -->
    @include('accounts.partials.subnav')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-building-columns text-purple-600"></i>
                <span>Institutional Bank Accounts</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage operational current accounts, fee escrow accounts, and live ledger balances</p>
        </div>
        <div>
            <button onclick="document.getElementById('modal-add-bank').classList.remove('hidden')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Register Bank Account</span>
            </button>
        </div>
    </div>

    <!-- Live Total Balance Card -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 p-6 rounded-2xl text-white shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <span class="text-xs uppercase tracking-wider font-bold text-indigo-300">Total Liquid Bank Balance</span>
            <div class="text-3xl font-black tracking-tight text-white">₹{{ number_format($totalBalance, 2) }}</div>
            <p class="text-[11px] text-slate-400">Consolidated balances across {{ $accounts->count() }} institutional banking channels</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('accounts.incomes.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-3 py-2 rounded-xl transition border border-white/10">
                Incomes Ledger
            </a>
            <a href="{{ route('accounts.expenses.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-3 py-2 rounded-xl transition border border-white/10">
                Expenses Ledger
            </a>
        </div>
    </div>

    <!-- Data Table (Exact match to demo) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Bank Accounts Registry ({{ $accounts->count() }})</span>
            <span class="text-[11px] text-slate-400">Live reconciliation balances verified</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Account Name</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Bank / Branch</th>
                        <th class="py-3 px-4">Account No</th>
                        <th class="py-3 px-4 text-right">Current Balance</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($accounts as $acc)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            <div class="flex items-center space-x-2">
                                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-building-columns"></i>
                                </span>
                                <span>{{ $acc->account_name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase border border-slate-200">
                                {{ $acc->account_type ?? 'Current' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-700">{{ $acc->bank_name }}</div>
                            <div class="text-[10px] text-slate-400">{{ $acc->branch ?? 'Main Branch' }} @if($acc->ifsc_code) &bull; <span class="font-mono">{{ $acc->ifsc_code }}</span> @endif</div>
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                            {{ $acc->account_number }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-extrabold text-sm text-slate-800 whitespace-nowrap">
                            ₹{{ number_format($acc->current_balance, 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <form action="{{ route('accounts.bank-accounts.destroy', $acc->id) }}" method="POST" onsubmit="return confirm('Remove this bank account?');" class="inline">
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
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            No bank accounts registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Bank Account -->
<div id="modal-add-bank" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full overflow-hidden text-xs">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-building-columns text-purple-600"></i>
                <span>Register Institutional Bank Account</span>
            </h3>
            <button onclick="document.getElementById('modal-add-bank').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('accounts.bank-accounts.store') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Account Name <span class="text-red-500">*</span></label>
                <input type="text" name="account_name" required placeholder="e.g. Operations Current A/c, Student Escrow" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-purple-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Account Type <span class="text-red-500">*</span></label>
                    <select name="account_type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-2 text-xs focus:outline-purple-500">
                        <option value="Current">Current Account</option>
                        <option value="Savings">Savings Account</option>
                        <option value="Escrow">Escrow Account</option>
                        <option value="Cash Credit">Cash Credit (OD)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bank Name <span class="text-red-500">*</span></label>
                    <input type="text" name="bank_name" required placeholder="e.g. HDFC Bank, SBI" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-purple-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Account Number <span class="text-red-500">*</span></label>
                <input type="text" name="account_number" required placeholder="e.g. 50200084930129" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-purple-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Branch / IFSC</label>
                    <input type="text" name="branch" placeholder="e.g. Civil Lines / HDFC0000021" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-purple-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Opening Balance (₹)</label>
                    <input type="number" step="0.01" name="opening_balance" placeholder="0.00" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono focus:outline-purple-500">
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-add-bank').classList.add('hidden')" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Cancel</button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-1.5 rounded-lg shadow-xs transition">Save Account</button>
            </div>
        </form>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Communication Wallet')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Communicate</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Comms Wallet</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-green-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Communication Wallet</h1>
                    <p class="text-xs text-slate-500">Prepaid balance for high-delivery SMS, WhatsApp notifications, and platform channels.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="openModal('settingsModal')" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-sliders text-slate-500"></i>
                <span>Settings</span>
            </button>
            <button type="button" onclick="openModal('topupModal')" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-emerald-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Add Money</span>
            </button>
        </div>
    </div>

    <!-- 3 High-Impact KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Current Balance -->
        <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-md flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-semibold text-indigo-300 uppercase tracking-wider">Current Balance</div>
                <div class="text-3xl font-black tracking-tight text-emerald-400">
                    ₹{{ number_format($wallet->balance, 2) }}
                </div>
                <div class="text-[11px] text-slate-400">
                    &asymp; {{ number_format($wallet->balance / 0.85) }} WhatsApp / {{ number_format($wallet->balance / 0.25) }} SMS messages
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-emerald-400 text-2xl">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>

        <!-- Spent Last 30 Days -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Spent &bull; Last 30 Days</div>
                <div class="text-2xl font-bold text-slate-900">
                    ₹{{ number_format($spentLast30, 2) }}
                </div>
                <div class="text-[11px] text-slate-400">
                    Auto-deducted per broadcast
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
        </div>

        <!-- Total Added -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Recharged</div>
                <div class="text-2xl font-bold text-slate-900">
                    ₹{{ number_format($totalAdded, 2) }}
                </div>
                <div class="text-[11px] text-emerald-600 font-semibold flex items-center">
                    <i class="fa-solid fa-shield-check mr-1"></i> Lifetime recharge credits
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
        </div>
    </div>

    <!-- Quick Top-Up Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Instant Wallet Recharge</h3>
            <p class="text-xs text-slate-500 mt-0.5">Select a quick amount or specify custom credits for campus messaging.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="quickTopup(100)" class="px-3.5 py-1.5 rounded-lg border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-xs font-bold text-slate-700 transition">
                + ₹100
            </button>
            <button type="button" onclick="quickTopup(250)" class="px-3.5 py-1.5 rounded-lg border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-xs font-bold text-slate-700 transition">
                + ₹250
            </button>
            <button type="button" onclick="quickTopup(500)" class="px-3.5 py-1.5 rounded-lg border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-xs font-bold text-slate-700 transition">
                + ₹500
            </button>
            <button type="button" onclick="quickTopup(1000)" class="px-3.5 py-1.5 rounded-lg border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-xs font-bold text-slate-700 transition">
                + ₹1000
            </button>
            <button type="button" onclick="openModal('topupModal')" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm shadow-emerald-500/20">
                Custom Recharge
            </button>
        </div>
    </div>

    <!-- Search & Channel Filter for Transactions -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('school.comms') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Search Notes or Transactions</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search notes, category, reference…" class="w-full pl-8 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Filter Channel / Type</label>
                <select name="channel" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none">
                    <option value="all">All Channels &amp; Types</option>
                    <option value="WhatsApp" {{ request('channel') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp Messages</option>
                    <option value="SMS" {{ request('channel') == 'SMS' ? 'selected' : '' }}>SMS Gateways</option>
                    <option value="Top-up" {{ request('channel') == 'Top-up' ? 'selected' : '' }}>Wallet Top-ups</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('school.comms') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transaction History Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                Transaction Statement
            </h3>
            <span class="text-xs text-slate-500 font-mono">{{ $transactions->total() }} recorded entries</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Transaction Details</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4 text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($transactions as $index => $txn)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-center text-slate-500 font-mono">
                            {{ $transactions->firstItem() + $index }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-xs">
                                {{ $txn->transaction_title }}
                            </div>
                            @if($txn->reference_id)
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                    Ref: {{ $txn->reference_id }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($txn->amount > 0)
                                <span class="bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded text-[10px]">
                                    {{ $txn->transaction_type }}
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-700 font-medium px-2 py-0.5 rounded text-[10px]">
                                    {{ $txn->transaction_type }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-600 whitespace-nowrap">
                            {{ date('d M Y, h:i A', strtotime($txn->created_at)) }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold whitespace-nowrap {{ $txn->amount > 0 ? 'text-emerald-600' : 'text-slate-800' }}">
                            {{ $txn->amount > 0 ? '+' : '−' }}₹{{ number_format(abs($txn->amount), 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                            ₹{{ number_format($txn->balance_after, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-receipt text-4xl mb-3 block text-slate-300"></i>
                            <p class="font-semibold text-slate-600 text-sm">No transactions yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal: Top-up Wallet -->
<div id="topupModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recharge Comms Wallet</h3>
            <button onclick="closeModal('topupModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('school.comms.topup') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Recharge Amount (INR) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-slate-500 font-bold text-sm">₹</span>
                    <input type="number" step="0.01" min="10" name="amount" id="topupAmountInput" required value="500" placeholder="Enter amount" class="w-full pl-8 pr-3 py-2 text-sm font-bold border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button type="button" onclick="document.getElementById('topupAmountInput').value='100'" class="flex-1 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded">₹100</button>
                <button type="button" onclick="document.getElementById('topupAmountInput').value='250'" class="flex-1 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded">₹250</button>
                <button type="button" onclick="document.getElementById('topupAmountInput').value='500'" class="flex-1 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded">₹500</button>
                <button type="button" onclick="document.getElementById('topupAmountInput').value='1000'" class="flex-1 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded">₹1000</button>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Payment Method</label>
                <select name="gateway" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none">
                    <option value="Razorpay">Razorpay (Cards, UPI, NetBanking)</option>
                    <option value="Stripe">Stripe (International Credit / Debit Cards)</option>
                    <option value="Manual">Manual Bank Transfer / Gate Cash Counter</option>
                </select>
            </div>

            <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-[11px] leading-relaxed">
                <i class="fa-solid fa-bolt mr-1"></i> Credits are added instantaneously to your account with auto-generated GST tax receipt.
            </div>

            <div class="flex justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('topupModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-lock mr-1.5"></i> Proceed to Pay
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Settings & Channel Sources -->
<div id="settingsModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Comms Settings &amp; Safety Controls</h3>
            <button onclick="closeModal('settingsModal')" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <!-- 1. Channel Source Form -->
        <form action="{{ route('school.comms.channel') }}" method="POST" class="space-y-3 pb-4 border-b border-slate-100">
            @csrf
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Platform Messaging Channels</h4>
            <div class="space-y-2">
                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="use_platform_whatsapp" value="1" {{ $wallet->use_platform_whatsapp ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                    <span>Use Built-in Meta Cloud WhatsApp Gateway (No API key needed)</span>
                </label>
                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="use_platform_sms" value="1" {{ $wallet->use_platform_sms ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                    <span>Use Built-in DLT High-Speed SMS Gateway</span>
                </label>
            </div>
            <button type="submit" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-lg transition">
                Save Channel Sources
            </button>
        </form>

        <!-- 2. Wallet Thresholds Form -->
        <form action="{{ route('school.comms.settings') }}" method="POST" class="space-y-4">
            @csrf
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Balance Safety Thresholds</h4>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Low Balance Alert Threshold (₹)</label>
                <input type="number" name="low_balance_threshold" value="{{ $wallet->low_balance_threshold }}" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none">
                <small class="text-[10px] text-slate-400">Sends alert email to administrator when balance drops below this amount.</small>
            </div>
            <div>
                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="auto_block" value="1" {{ $wallet->auto_block ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                    <span>Auto-block broadcast dispatch if balance is below threshold</span>
                </label>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeModal('settingsModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                    Close
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function quickTopup(amt) {
    document.getElementById('topupAmountInput').value = amt;
    openModal('topupModal');
}
</script>
@endsection


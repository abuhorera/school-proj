@extends('layouts.app')

@section('title', 'Fees Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                <i class="fa-solid fa-wallet"></i>
                <span>Finance & Fees • Real-time Financial Engine</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Fees Executive Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time revenue monitoring, student fee dues, collection velocity, and top defaulters.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('fees.collect') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-receipt"></i>
                <span>⚡ Collect Fees (POS)</span>
            </a>
            <a href="{{ route('fees.due') }}" class="bg-slate-800 hover:bg-slate-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-magnifying-glass-dollar"></i>
                <span>Search Due Fees</span>
            </a>
        </div>
    </div>

    <!-- 6 Primary Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Total Expected</span>
                <i class="fa-solid fa-calculator text-slate-400 text-xs"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">₹{{ number_format($totalAllocations, 0) }}</h3>
            <span class="text-[10px] text-slate-500 font-medium">Session 2026-27</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-200 bg-emerald-50/20">
            <div class="flex items-center justify-between text-emerald-600 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Total Collected</span>
                <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i>
            </div>
            <h3 class="text-xl font-bold text-emerald-700">₹{{ number_format($totalCollected, 0) }}</h3>
            <span class="text-[10px] text-emerald-600 font-semibold">{{ $totalAllocations > 0 ? round(($totalCollected/$totalAllocations)*100, 1) : 0 }}% Realized</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-red-200 bg-red-50/20">
            <div class="flex items-center justify-between text-red-600 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Outstanding Due</span>
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-xs"></i>
            </div>
            <h3 class="text-xl font-bold text-red-700">₹{{ number_format($totalDue, 0) }}</h3>
            <span class="text-[10px] text-red-600 font-semibold">Immediate Follow-up</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Collected Today</span>
                <i class="fa-solid fa-calendar-day text-blue-500 text-xs"></i>
            </div>
            <h3 class="text-xl font-bold text-blue-700">₹{{ number_format($collectedToday, 0) }}</h3>
            <span class="text-[10px] text-slate-500 font-medium">{{ date('d M Y') }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Discounts / Waivers</span>
                <i class="fa-solid fa-tag text-purple-500 text-xs"></i>
            </div>
            <h3 class="text-xl font-bold text-purple-700">₹{{ number_format($totalDiscounts, 0) }}</h3>
            <span class="text-[10px] text-purple-600 font-medium">Scholarships</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between text-slate-400 mb-1">
                <span class="text-[10px] font-bold uppercase tracking-wider">Online Gateway</span>
                <i class="fa-brands fa-google-pay text-cyan-600 text-sm"></i>
            </div>
            <h3 class="text-xl font-bold text-cyan-700">₹{{ number_format($onlineCollected, 0) }}</h3>
            <span class="text-[10px] text-cyan-600 font-medium">Razorpay / UPI</span>
        </div>
    </div>

    <!-- Quick Sub-Menu Action Grid (Direct Shortcuts to All 16 Modules) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 text-center">
        <a href="{{ route('fees.collect') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:shadow-sm transition">
            <i class="fa-solid fa-cash-register text-emerald-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Collect POS</div>
        </a>
        <a href="{{ route('fees.due') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-red-500 hover:shadow-sm transition">
            <i class="fa-solid fa-file-invoice-dollar text-red-500 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Search Due</div>
        </a>
        <a href="{{ route('fees.transactions') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-blue-500 hover:shadow-sm transition">
            <i class="fa-solid fa-receipt text-blue-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Transactions</div>
        </a>
        <a href="{{ route('fees.online') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-cyan-500 hover:shadow-sm transition">
            <i class="fa-solid fa-globe text-cyan-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Online Pay</div>
        </a>
        <a href="{{ route('fees.challans') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-sm transition">
            <i class="fa-solid fa-file-invoice text-indigo-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Challans</div>
        </a>
        <a href="{{ route('fees.assign') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-purple-500 hover:shadow-sm transition">
            <i class="fa-solid fa-user-check text-purple-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Assign Fees</div>
        </a>
        <a href="{{ route('fees.due-slips') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-amber-500 hover:shadow-sm transition">
            <i class="fa-solid fa-envelope-open-text text-amber-600 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Due Slips</div>
        </a>
        <a href="{{ route('fees.fine-rules') }}" class="bg-white p-3 rounded-xl border border-slate-200 hover:border-rose-500 hover:shadow-sm transition">
            <i class="fa-solid fa-clock text-rose-500 text-base mb-1"></i>
            <div class="text-[11px] font-bold text-slate-800">Fine Rules</div>
        </a>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Monthly Collection Trend -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div>
                    <h3 class="font-bold text-sm text-slate-800">15-Day Revenue Velocity</h3>
                    <p class="text-xs text-slate-500">Daily fee collection aggregate across all payment counters</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-100 text-slate-700">November 2026</span>
            </div>
            <div class="h-64">
                <canvas id="feeTrendChart"></canvas>
            </div>
        </div>

        <!-- Payment Mode Distribution -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="pb-3 border-b border-slate-100 mb-3">
                <h3 class="font-bold text-sm text-slate-800">Collection by Payment Mode</h3>
                <p class="text-xs text-slate-500">Cash vs UPI vs Net Banking vs Cheque</p>
            </div>
            <div class="h-48 flex items-center justify-center">
                <canvas id="feeModeChart"></canvas>
            </div>
            <div class="pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase">Cash POS</span>
                    <div class="font-bold text-slate-800">65%</div>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase">UPI QR</span>
                    <div class="font-bold text-emerald-600">25%</div>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase">Bank / Chq</span>
                    <div class="font-bold text-indigo-600">10%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Fee Defaulters Table (Exact Match to Demo) -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-sm text-slate-800">Top Unpaid Fee Accounts (Defaulters)</h3>
                <p class="text-xs text-slate-500">Students with longest pending arrears requiring immediate follow-up</p>
            </div>
            <a href="{{ route('fees.due') }}" class="text-xs text-blue-600 font-bold hover:underline">View All Due Accounts →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Student Name</th>
                        <th class="py-3 px-4">Adm. No</th>
                        <th class="py-3 px-4">Class</th>
                        <th class="py-3 px-4">Unpaid Since</th>
                        <th class="py-3 px-4 text-right">Due Amount</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($defaulters as $st)
                    @php
                        $alloc = $st->feeAllocations->first();
                        $due = $alloc ? $alloc->final_amount : 12500;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-bold text-slate-800 flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs">
                                {{ substr($st->first_name, 0, 1) }}
                            </div>
                            <span>{{ $st->first_name }} {{ $st->last_name }}</span>
                        </td>
                        <td class="py-3 px-4 font-mono font-medium text-slate-600">{{ $st->admission_no }}</td>
                        <td class="py-3 px-4 text-slate-700">{{ $st->latestEnrollment->schoolClass->name ?? 'Class 10' }}</td>
                        <td class="py-3 px-4 text-slate-500">01 Oct 2026 (35 Days)</td>
                        <td class="py-3 px-4 text-right font-bold text-red-600">₹{{ number_format($due, 2) }}</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('fees.collect', ['student_id' => $st->id]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2.5 py-1 rounded text-[11px] shadow-sm">
                                Collect Now
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400">All fees up to date. Zero defaulters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 15-Day Revenue Trend Chart
    const ctxTrend = document.getElementById('feeTrendChart');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Oct 22', 'Oct 24', 'Oct 26', 'Oct 28', 'Oct 30', 'Nov 01', 'Nov 03', 'Nov 05'],
                datasets: [{
                    label: 'Collection (₹)',
                    data: [42000, 78000, 65000, 110000, 89000, 145000, 92000, 134500],
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.1)',
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#059669'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '₹' + v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Payment Mode Donut Chart
    const ctxMode = document.getElementById('feeModeChart');
    if (ctxMode) {
        new Chart(ctxMode, {
            type: 'doughnut',
            data: {
                labels: ['Cash', 'UPI QR', 'Bank Transfer', 'Cheque'],
                datasets: [{
                    data: [65, 25, 7, 3],
                    backgroundColor: ['#10b981', '#06b6d4', '#6366f1', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection


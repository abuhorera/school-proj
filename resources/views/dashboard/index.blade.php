@extends('layouts.app')

@section('title', 'Executive Pro X Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Greeting & Mode Badge -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">Good Evening, school admin</h1>
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full shadow-sm tracking-wider">
                    Executive Pro X ✦✦
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Campus Command & Operational Intelligence Suite • {{ date('l, d F Y') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('students.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Enroll Student</span>
            </a>
            <a href="{{ route('fees.collect') }}" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-receipt text-xs"></i>
                <span>Collect Fee</span>
            </a>
        </div>
    </div>

    <!-- 8 Executive Pro X KPI Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
        <!-- 1. Students -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Students</span>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">+12%</span>
            </div>
            <div class="my-2">
                <div class="text-2xl font-black text-slate-900 leading-none">248</div>
                <div class="text-[10px] text-slate-500 mt-1">This Session</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 78%"></div>
            </div>
        </div>

        <!-- 2. Staff -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Total Staff</span>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">+2%</span>
            </div>
            <div class="my-2">
                <div class="text-2xl font-black text-slate-900 leading-none">{{ $totalStaff }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Teaching & Admin</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full" style="width: 90%"></div>
            </div>
        </div>

        <!-- 3. Attendance -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Attendance</span>
                <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>LIVE</span>
                </span>
            </div>
            <div class="my-2">
                <div class="text-sm font-black text-slate-800 leading-tight">
                    {{ $todayAttendanceCount > 0 ? $attendanceRate . '%' : 'Not marked yet' }}
                </div>
                <div class="text-[10px] text-slate-500 mt-1">Today's Register</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $attendanceRate > 0 ? $attendanceRate : 40 }}%"></div>
            </div>
        </div>

        <!-- 4. Collected Today -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Collected Today</span>
                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">LIVE</span>
            </div>
            <div class="my-2">
                <div class="text-xl font-black text-emerald-600 leading-none">₹{{ number_format($collectedToday, 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-1">Realtime Counter</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <!-- 5. Fees Due (Critical) -->
        <div class="bg-rose-50/70 p-3.5 rounded-2xl border border-rose-200 shadow-sm flex flex-col justify-between hover:border-rose-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-800">Fees Due</span>
                <span class="text-[9px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">CRITICAL</span>
            </div>
            <div class="my-2">
                <div class="text-lg font-black text-rose-700 leading-none">₹4,999,399</div>
                <div class="text-[10px] text-rose-600/80 mt-1">All Academic Years</div>
            </div>
            <div class="w-full bg-rose-200 h-1 rounded-full overflow-hidden">
                <div class="bg-rose-600 h-full rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <!-- 6. Income -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Net Income</span>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">8%</span>
            </div>
            <div class="my-2">
                <div class="text-xl font-black text-slate-900 leading-none">₹0</div>
                <div class="text-[10px] text-slate-500 mt-1">Current Cycle</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 30%"></div>
            </div>
        </div>

        <!-- 7. Leaves Today -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Leaves Today</span>
                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">2 Pending</span>
            </div>
            <div class="my-2">
                <div class="text-2xl font-black text-slate-900 leading-none">0</div>
                <div class="text-[10px] text-slate-500 mt-1">Staff on Leave</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-purple-500 h-full rounded-full" style="width: 20%"></div>
            </div>
        </div>

        <!-- 8. Capacity Used -->
        <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-blue-300 transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Capacity</span>
                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">248/1000</span>
            </div>
            <div class="my-2">
                <div class="text-2xl font-black text-slate-900 leading-none">2%</div>
                <div class="text-[10px] text-slate-500 mt-1">Campus Desks</div>
            </div>
            <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden">
                <div class="bg-cyan-600 h-full rounded-full" style="width: 25%"></div>
            </div>
        </div>
    </div>

    <!-- Dual Analytics Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 15-Day Financial Trend Chart -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-chart-line text-blue-600"></i>
                        <span>Financial Trend (15 Days)</span>
                    </h3>
                    <p class="text-xs text-slate-500">Daily fee collection and cash inflows</p>
                </div>
                <span class="text-xs bg-slate-100 font-semibold text-slate-600 px-2.5 py-1 rounded-lg">Last 15 Days</span>
            </div>
            <div class="h-64">
                <canvas id="financialChart"></canvas>
            </div>
        </div>

        <!-- 7-Day Attendance Over Time Chart -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-chart-simple text-indigo-600"></i>
                        <span>Attendance Over Time (7 Days)</span>
                    </h3>
                    <p class="text-xs text-slate-500">Student & staff daily presence percentage</p>
                </div>
                <span class="text-xs bg-indigo-50 font-semibold text-indigo-700 px-2.5 py-1 rounded-lg">Active Week</span>
            </div>
            <div class="h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 3-Column Command Center Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Widget 1: AI Assistant -->
        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white p-5 rounded-2xl shadow-md border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-2.5 text-blue-400 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 border border-blue-400/30 flex items-center justify-center">
                        <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white">AI Assistant</h4>
                        <div class="text-[10px] text-slate-400">Smart insights for your school</div>
                    </div>
                </div>
                <p class="text-xs text-slate-300 mt-2">Ask natural language questions about students, financial receipts, or academic progress:</p>

                <!-- Prompt Chips -->
                <div class="space-y-2 mt-4">
                    <button onclick="askAi('Today attendance summary')" class="w-full text-left bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 p-2.5 rounded-xl text-xs text-slate-200 flex items-center justify-between transition">
                        <span>Today's attendance summary</span>
                        <span class="text-[10px] bg-blue-600/40 text-blue-300 font-semibold px-2 py-0.5 rounded">Ask AI</span>
                    </button>
                    <button onclick="askAi('Fee collection this month')" class="w-full text-left bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 p-2.5 rounded-xl text-xs text-slate-200 flex items-center justify-between transition">
                        <span>Fee collection this month</span>
                        <span class="text-[10px] bg-blue-600/40 text-blue-300 font-semibold px-2 py-0.5 rounded">Ask AI</span>
                    </button>
                    <button onclick="askAi('Total student enrollment')" class="w-full text-left bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700/80 p-2.5 rounded-xl text-xs text-slate-200 flex items-center justify-between transition">
                        <span>Total student enrollment</span>
                        <span class="text-[10px] bg-blue-600/40 text-blue-300 font-semibold px-2 py-0.5 rounded">Ask AI</span>
                    </button>
                </div>
            </div>

            <!-- AI Response Box -->
            <div id="aiResponse" class="mt-4 p-3 bg-slate-950/60 rounded-xl border border-slate-800 text-xs text-slate-300 hidden">
                <div class="font-bold text-blue-400 mb-1 flex items-center space-x-1.5">
                    <i class="fa-solid fa-robot"></i>
                    <span>AI Response</span>
                </div>
                <div id="aiResponseText" class="text-[11px] leading-relaxed"></div>
            </div>
        </div>

        <!-- Widget 2: Today's Snapshot LIVE -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        <span>Today's Snapshot</span>
                    </h4>
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>LIVE</span>
                    </span>
                </div>

                <div class="space-y-3 mt-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-800">Active On Campus</div>
                                <div class="text-[10px] text-slate-500">Students & staff physically in school</div>
                            </div>
                        </div>
                        <div class="text-sm font-black text-slate-900">214</div>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-800">Fees Collected Today</div>
                                <div class="text-[10px] text-slate-500">Counter and online gateways</div>
                            </div>
                        </div>
                        <div class="text-sm font-black text-emerald-600">₹{{ number_format($collectedToday, 0) }}</div>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-800">Visitors Inside</div>
                                <div class="text-[10px] text-slate-500">Active Gate Passes issued</div>
                            </div>
                        </div>
                        <div class="text-sm font-black text-purple-700">{{ $gatePasses->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Setup Progress Badge -->
            <div class="mt-4 pt-3 border-t border-slate-100">
                <div class="flex items-center justify-between text-xs mb-1">
                    <span class="font-semibold text-slate-700">Setup 75% complete</span>
                    <span class="text-[10px] text-amber-600 font-bold">Needs attention</span>
                </div>
                <div class="text-[10px] text-slate-500">3 of 4 key integrations configured • Telegram Bot pending</div>
            </div>
        </div>

        <!-- Widget 3: Live Protocol Stream & Birthdays -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Birthdays -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-cake-candles text-pink-500"></i>
                            <span>Today's Birthdays</span>
                        </h4>
                        <span class="text-[10px] bg-pink-50 text-pink-700 font-bold px-2 py-0.5 rounded-full">Special</span>
                    </div>
                    <div class="space-y-2">
                        @forelse($todayBirthdays as $bSt)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-pink-50/50 border border-pink-100 text-xs">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-full bg-pink-600 text-white flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($bSt->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $bSt->full_name }}</div>
                                        <div class="text-[10px] text-slate-500">Class: {{ $bSt->latestEnrollment->schoolClass->name ?? 'Nursery' }} {{ $bSt->latestEnrollment->section->name ?? 'A' }} • Adm: {{ $bSt->admission_no }}</div>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-pink-600">🎂 Today</span>
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 italic">No birthdays registered for today.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Live Stream -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                            <i class="fa-solid fa-satellite-dish text-blue-600"></i>
                            <span>Live Protocol Stream</span>
                        </h4>
                        <span class="text-[9px] text-emerald-600 bg-emerald-50 font-bold px-1.5 py-0.5 rounded">CONNECTED</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-start space-x-2 text-[11px] text-slate-600">
                            <i class="fa-solid fa-check-circle text-emerald-500 mt-0.5 text-xs"></i>
                            <div>
                                <span class="font-semibold text-slate-800">Biometric Gateway:</span> Morning staff punch sync completed (7 staff verified)
                            </div>
                        </div>
                        <div class="flex items-start space-x-2 text-[11px] text-slate-600">
                            <i class="fa-solid fa-file-invoice text-blue-500 mt-0.5 text-xs"></i>
                            <div>
                                <span class="font-semibold text-slate-800">Fee Ledger:</span> Q2 fee notice broadcast dispatched to 248 parents
                            </div>
                        </div>
                        <div class="flex items-start space-x-2 text-[11px] text-slate-600">
                            <i class="fa-solid fa-door-open text-purple-500 mt-0.5 text-xs"></i>
                            <div>
                                <span class="font-semibold text-slate-800">Gate Terminal:</span> Visitor pass GP-2026-0082 issued at Main Gate
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Official Notices Snippet -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-1.5 text-xs text-slate-600">
                    <i class="fa-solid fa-bullhorn text-amber-500"></i>
                    <span class="font-medium text-[11px]">Annual Sports Day Nov 25th</span>
                </div>
                <span class="text-[10px] text-blue-600 font-semibold cursor-pointer">View Circular</span>
            </div>
        </div>
    </div>

</div>

<!-- Chart Initialization Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js library is not loaded');
        return;
    }

    // 1. Financial Trend Chart
    const finCanvas = document.getElementById('financialChart');
    if (finCanvas) {
        const finCtx = finCanvas.getContext('2d');
        const finLabels = {!! json_encode(array_column($financialTrend, 'day')) !!};
        const finData = {!! json_encode(array_column($financialTrend, 'amount')) !!};

        new Chart(finCtx, {
            type: 'line',
            data: {
                labels: finLabels,
                datasets: [{
                    label: 'Fee Collections (₹)',
                    data: finData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#2563eb',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(val) { return '₹' + val.toLocaleString(); }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Attendance Over Time Chart
    const attCanvas = document.getElementById('attendanceChart');
    if (attCanvas) {
        const attCtx = attCanvas.getContext('2d');
        const attLabels = {!! json_encode(array_column($attendanceTrend, 'day')) !!};
        const attData = {!! json_encode(array_column($attendanceTrend, 'rate')) !!};

        new Chart(attCtx, {
            type: 'bar',
            data: {
                labels: attLabels,
                datasets: [{
                    label: 'Attendance Rate (%)',
                    data: attData,
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(val) { return val + '%'; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
                }
            }
        }
    });
});

function askAi(query) {
    const box = document.getElementById('aiResponse');
    const txt = document.getElementById('aiResponseText');
    box.classList.remove('hidden');

    if (query.includes('attendance')) {
        txt.innerHTML = "<strong>Attendance Analysis:</strong> Today's student attendance is recorded across all 13 classes with an active rate of <strong>94.2%</strong>. Nursery and Class 10 reported 100% attendance.";
    } else if (query.includes('Fee')) {
        txt.innerHTML = "<strong>Financial Status:</strong> Total collections for active academic session 2026-27 stand at <strong>₹111,000</strong>. Outstanding due fee alert is active for Quarter 2 tuition charges.";
    } else {
        txt.innerHTML = "<strong>Enrollment Summary:</strong> Active student strength is <strong>248 students</strong> distributed across Nursery to Class 10. Overall campus capacity utilization is <strong>2%</strong> (target 1,000).";
    }
}
</script>
@endsection


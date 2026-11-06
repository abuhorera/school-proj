@extends('layouts.app')

@section('title', 'Transport Management Guide')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('transport.nav')

    <!-- Hero Card -->
    <div class="ts-card border-0 shadow-sm overflow-hidden mb-4 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-5 rounded-2xl">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-3">
                    🚌 Transport Fleet & Routing Engine
                </span>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-3">
                    One module. <span class="text-amber-400">From depot to doorstep</span> — tracked, billed & safe.
                </h1>
                <p class="text-slate-300 text-sm md:text-base leading-relaxed mb-4">
                    Run your entire school fleet end-to-end: <strong>vehicles</strong> carry <strong>routes</strong>, routes have ordered <strong>stops</strong>, and one student assignment both registers the child on the bus manifest <em>and</em> generates their recurring ledger fee. Coupled with <strong>live GPS telemetry</strong>, mobile driver terminal support, and a tamper-proof <strong>boarding register</strong>.
                </p>
                <div class="d-flex flex-wrap gap-4 pt-2">
                    <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 border border-white/10">
                        <div class="text-xl font-black text-amber-400">Live</div>
                        <div class="text-xs text-slate-300 font-medium">GPS Bus Tracking</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 border border-white/10">
                        <div class="text-xl font-black text-emerald-400">Auto</div>
                        <div class="text-xs text-slate-300 font-medium">Monthly Fee Billing</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 border border-white/10">
                        <div class="text-xl font-black text-sky-400">Unlimited</div>
                        <div class="text-xs text-slate-300 font-medium">Routes · Stops · Fleet</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 border border-white/10">
                        <div class="text-xl font-black text-purple-400">1-Tap</div>
                        <div class="text-xs text-slate-300 font-medium">Boarding → Attendance</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="inline-block p-4 bg-white/5 rounded-2xl border border-white/10 text-center">
                    <i class="fas fa-bus-alt text-amber-400 text-6xl mb-2 animate-bounce"></i>
                    <div class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Multi-School Fleet OS</div>
                    <div class="text-sm font-bold text-white mt-1">Real-time Telemetry v3.2</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation / Table of Contents -->
    <div class="ts-card p-4 mb-4">
        <h6 class="font-weight-bold text-dark text-xs uppercase tracking-wider mb-3">
            <i class="fas fa-list-ul text-amber-500 mr-1.5"></i> Table of Contents & Quick Jumps
        </h6>
        <div class="row text-sm">
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-power" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-bolt text-amber-500"></i> 1. Why this module is powerful
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-architecture" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-sitemap text-indigo-500"></i> 2. The Big Picture & Workflow
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-fleet" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-van-shuttle text-blue-500"></i> 3. Vehicles & Drivers
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-routes" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-route text-emerald-500"></i> 4. Routes, Stops & Runs
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-billing" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-file-invoice-dollar text-rose-500"></i> 5. Automatic Fee Billing
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-tracking" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-satellite-dish text-teal-500"></i> 6. Live Tracking & Hardware
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-boarding" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-clipboard-user text-purple-500"></i> 7. Boarding & Attendance
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="#section-scenarios" class="text-slate-700 hover:text-amber-600 font-medium flex items-center gap-1.5">
                    <i class="fas fa-shield-alt text-cyan-500"></i> 8. Real-world Scenarios
                </a>
            </div>
        </div>
    </div>

    <!-- Section 1: Core Value -->
    <div id="section-power" class="ts-card mb-4">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500"></i>
                Why this module is powerful — a lot more than a simple route list
            </span>
        </div>
        <div class="p-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-3 rounded-lg border bg-slate-50 h-100">
                        <div class="text-amber-600 font-bold mb-1 flex items-center gap-2">
                            <i class="fas fa-link"></i> Automated Accounting Tie-in
                        </div>
                        <p class="text-xs text-slate-600 mb-0">
                            No manual fee ledger entries. Assigning a child to Stop #3 immediately writes 10 or 12 monthly fee dues to their student ledger based on that stop's exact fare.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3 rounded-lg border bg-slate-50 h-100">
                        <div class="text-emerald-600 font-bold mb-1 flex items-center gap-2">
                            <i class="fas fa-location-arrow"></i> Dual Telemetry Integration
                        </div>
                        <p class="text-xs text-slate-600 mb-0">
                            Supports physical hardware GPS OBD/wire trackers (via IMEI device pings) or smartphone GPS transmission from the driver mobile console.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-3 rounded-lg border bg-slate-50 h-100">
                        <div class="text-indigo-600 font-bold mb-1 flex items-center gap-2">
                            <i class="fas fa-user-shield"></i> Child Safety & Accountability
                        </div>
                        <p class="text-xs text-slate-600 mb-0">
                            Real-time student boarding checklists guarantee school administrators and parents know the exact minute a student boarded or was marked absent at a stop.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Architecture & Workflow -->
    <div id="section-architecture" class="ts-card mb-4">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fas fa-sitemap text-indigo-500"></i>
                The Big Picture: Step-by-Step Architecture
            </span>
        </div>
        <div class="p-4">
            <div class="row text-center">
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 text-slate-800 flex items-center justify-center font-bold mb-2">1</div>
                        <div class="font-bold text-xs text-dark">Register Fleet</div>
                        <div class="text-xs text-muted mt-1">Buses, Vans, Capacity & Drivers</div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold mb-2">2</div>
                        <div class="font-bold text-xs text-dark">Create Routes</div>
                        <div class="text-xs text-muted mt-1">Origin, Destination & Assigned Bus</div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-blue-100 text-blue-800 flex items-center justify-center font-bold mb-2">3</div>
                        <div class="font-bold text-xs text-dark">Define Stops</div>
                        <div class="text-xs text-muted mt-1">Order sequence, timings & fares</div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold mb-2">4</div>
                        <div class="font-bold text-xs text-dark">Assign Students</div>
                        <div class="text-xs text-muted mt-1">Bus pass issuance & ledger fees</div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-purple-100 text-purple-800 flex items-center justify-center font-bold mb-2">5</div>
                        <div class="font-bold text-xs text-dark">Daily Trips</div>
                        <div class="text-xs text-muted mt-1">Morning & afternoon scheduled runs</div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 border rounded-xl bg-white shadow-sm h-100">
                        <div class="w-10 h-10 mx-auto rounded-full bg-rose-100 text-rose-800 flex items-center justify-center font-bold mb-2">6</div>
                        <div class="font-bold text-xs text-dark">Live Telemetry</div>
                        <div class="text-xs text-muted mt-1">Real-time GPS map & boarding logs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Step-by-Step Sections -->
    <div class="row">
        <!-- Step 1 & 2 -->
        <div class="col-lg-6 mb-4" id="section-fleet">
            <div class="ts-card h-100">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-van-shuttle text-amber-500"></i>
                        1. Vehicles & Drivers Setup
                    </span>
                    <a href="{{ route('school.transport.vehicles') }}" class="btn btn-xs btn-outline-dark">Manage &rarr;</a>
                </div>
                <div class="p-4 text-sm text-slate-700">
                    <p>Every physical vehicle requires a registered registration number (e.g. <code>CG04HD7250</code>), seating capacity, insurance expiry, and driver link.</p>
                    <ul class="pl-3 mb-3 space-y-1">
                        <li><strong>Vehicle Passcode:</strong> Set a 4-digit driver login PIN for the mobile terminal app.</li>
                        <li><strong>GPS Device IMEI:</strong> Connect an external hardwired vehicle tracker for continuous live pings.</li>
                        <li><strong>Status Toggles:</strong> Switch vehicle to <em>In Service</em>, <em>On Trip</em>, or <em>Maintenance</em> to notify the morning watch desk.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 3: Routes & Stops -->
        <div class="col-lg-6 mb-4" id="section-routes">
            <div class="ts-card h-100">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-route text-emerald-500"></i>
                        2. Routes, Stops & Scheduled Runs
                    </span>
                    <a href="{{ route('school.transport.routes') }}" class="btn btn-xs btn-outline-dark">Manage &rarr;</a>
                </div>
                <div class="p-4 text-sm text-slate-700">
                    <p>A <strong>Route</strong> connects multiple geographic pick-up landmarks in a specific traversal order.</p>
                    <ul class="pl-3 mb-3 space-y-1">
                        <li><strong>Runs / Schedules:</strong> Typically 2 runs per route — <em>Morning Inbound</em> (07:00 AM) and <em>Afternoon Outbound</em> (02:00 PM).</li>
                        <li><strong>Stops & Fares:</strong> Each stop has an arrival time and designated monthly price (e.g. Stop A: ₹800, Stop B: ₹1,200).</li>
                        <li><strong>Reordering:</strong> Maintain ordered sequences to calculate driver delays and route punctuality.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 4: Billing -->
        <div class="col-lg-6 mb-4" id="section-billing">
            <div class="ts-card h-100">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-rose-500"></i>
                        3. Transport Fee Settings & Automated Billing
                    </span>
                    <a href="{{ route('school.transport.fee-settings') }}" class="btn btn-xs btn-outline-dark">Settings &rarr;</a>
                </div>
                <div class="p-4 text-sm text-slate-700">
                    <p>Transport fees are seamlessly tied to the institutional accounts ledger.</p>
                    <ul class="pl-3 mb-3 space-y-1">
                        <li><strong>Auto-Billing Rule:</strong> When active, assigning a student automatically produces fee invoices for each month of the active session.</li>
                        <li><strong>Prorated Joining:</strong> If a student joins mid-term, the engine generates fees strictly for future months without unfair back-charges.</li>
                        <li><strong>Safe Cancellation:</strong> Stopping transport flags future unpaid vouchers as cancelled while preserving settled receipts.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 5: Live Telemetry -->
        <div class="col-lg-6 mb-4" id="section-tracking">
            <div class="ts-card h-100">
                <div class="ts-section-title">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-satellite-dish text-teal-500"></i>
                        4. Live Telemetry & GPS Tracking
                    </span>
                    <a href="{{ route('school.transport.tracking') }}" class="btn btn-xs btn-outline-dark">Live Map &rarr;</a>
                </div>
                <div class="p-4 text-sm text-slate-700">
                    <p>The interactive GPS tracking canvas displays instant vehicle coordinates, ground speed, and route path.</p>
                    <ul class="pl-3 mb-3 space-y-1">
                        <li><strong>Real-time Speedometer:</strong> Monitors speed violations and driver route adherence.</li>
                        <li><strong>Telemetry REST Endpoint:</strong> Accepts HTTP POST coordinate pings directly from GPS trackers or phones.</li>
                        <li><strong>Live Operations Watch:</strong> Single-pane monitor showing punctuality, delays, and current bus runs.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Real World Scenarios -->
    <div id="section-scenarios" class="ts-card mb-4">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fas fa-lightbulb text-amber-500"></i>
                Real-World Operational Scenarios — All Fully Handled
            </span>
        </div>
        <div class="p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="border rounded-lg p-3 bg-slate-50">
                        <div class="font-bold text-dark mb-1 flex items-center gap-1.5">
                            <i class="fas fa-exchange-alt text-amber-500"></i>
                            Student Changes Residential Address or Bus Stop
                        </div>
                        <p class="text-xs text-slate-600 mb-0">
                            Simply edit the student's route assignment and select the new stop. The system recalculates upcoming monthly installment amounts automatically while leaving already cleared receipts unchanged.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="border rounded-lg p-3 bg-slate-50">
                        <div class="font-bold text-dark mb-1 flex items-center gap-1.5">
                            <i class="fas fa-wrench text-rose-500"></i>
                            Vehicle Breakdown & Temporary Bus Reassignment
                        </div>
                        <p class="text-xs text-slate-600 mb-0">
                            Mark the primary bus as <em>Maintenance</em> in Vehicle Management, and assign a reserve bus to the route with one click. Drivers, GPS streams, and student rosters transfer immediately.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


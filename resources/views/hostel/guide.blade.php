@extends('layouts.app')

@section('title', 'Hostel — Module Guide')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('hostel.nav')

    <style>
        .g-card {
            background: #ffffff;
            border: 1px solid var(--hst-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .g-head {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--hst-primary);
            padding: 16px 20px;
            border-bottom: 1px solid var(--hst-border);
            display: flex;
            align-items: center;
        }
        .g-body {
            padding: 24px;
            font-size: 0.92rem;
            color: #334155;
            line-height: 1.6;
        }
        .flow-step {
            text-align: center;
            padding: 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid var(--hst-border);
            height: 100%;
        }
        .flow-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--hst-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 10px;
            font-size: 0.85rem;
        }
    </style>

    <!-- Guide Hero Card -->
    <div class="g-card">
        <div class="g-head">
            <i class="fas fa-book-open text-amber-500 mr-2"></i>
            How the Hostel Module Works
        </div>
        <div class="g-body">
            <p class="mb-3">
                This module manages campus residential inventory and tracks <strong>who sleeps where</strong>. The architecture is a clean three-level hierarchy — <strong>Hostel &rarr; Room Type &rarr; Room &rarr; Student Allocation</strong>.
            </p>
            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-900 text-sm mb-0">
                <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                <strong>Setup Rule:</strong> Always configure in logical order — a room requires both a hostel block and a room type before it can exist, and a student needs an available room bed before allocation can take place.
            </div>
        </div>
    </div>

    <!-- The Big Picture Flow -->
    <div class="g-card">
        <div class="g-head">
            <i class="fas fa-sitemap text-indigo-500 mr-2"></i>
            The Big Picture & Hierarchy
        </div>
        <div class="g-body">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="flow-step">
                        <div class="flow-num">1</div>
                        <h6 class="font-weight-bold text-dark mb-1">Hostels</h6>
                        <small class="text-muted">The physical buildings (e.g. Boys Hostel, Gargi Hall) with block classifications.</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="flow-step">
                        <div class="flow-num">2</div>
                        <h6 class="font-weight-bold text-dark mb-1">Room Types</h6>
                        <small class="text-muted">Accommodation tiers (AC, Non-AC, Dormitory) with designated monthly/term fares.</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="flow-step">
                        <div class="flow-num">3</div>
                        <h6 class="font-weight-bold text-dark mb-1">Rooms</h6>
                        <small class="text-muted">Numbered units (e.g. Room 101) with defined bed capacities (e.g. 2, 4, or 6 beds).</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="flow-step">
                        <div class="flow-num">4</div>
                        <h6 class="font-weight-bold text-dark mb-1">Student Allocation</h6>
                        <small class="text-muted">Assigning an active student to an available bed in a room with instant occupancy recalculation.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading the Dashboard -->
    <div class="g-card">
        <div class="g-head">
            <i class="fas fa-tachometer-alt text-emerald-500 mr-2"></i>
            Reading the Live Dashboard
        </div>
        <div class="g-body p-0">
            <div class="table-responsive">
                <table class="table tpl-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 250px;">Figure / Metric</th>
                            <th>How It Is Derived</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Total Hostels & Rooms</td>
                            <td class="text-muted">Straight counts of all registered residential buildings and active rooms.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Total Capacity & Beds</td>
                            <td class="text-muted">The sum total of capacity numbers across every room configured in all hostels.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Occupied Beds</td>
                            <td class="text-muted">Live count of all active student allocations currently residing on campus.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Available Beds</td>
                            <td class="text-muted">Vacant spots calculated as <code>Total Capacity &minus; Occupied Beds</code>.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Occupancy Percentage</td>
                            <td class="text-muted">Visualized per-hostel progress bars tracking capacity utilization.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabs at a Glance -->
    <div class="g-card">
        <div class="g-head">
            <i class="fas fa-layer-group text-blue-500 mr-2"></i>
            Tabs & Modules at a Glance
        </div>
        <div class="g-body p-0">
            <div class="table-responsive">
                <table class="table tpl-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 220px;">Tab Name</th>
                            <th>Primary Actions & Responsibilities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Dashboard</td>
                            <td class="text-muted">Live summary stats, occupancy bars per hostel, and room availability table.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Student Allocation</td>
                            <td class="text-muted">Interactive room cards showing bed occupancy, resident student badges, and 1-click allocation/removal.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Manage Rooms</td>
                            <td class="text-muted">Rooms inventory table, modal room creator, capacity editor, and room type associations.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Room Types</td>
                            <td class="text-muted">AC, Non-AC, and Dormitory tiers with monthly fee rates and description tags.</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-slate-800">Manage Hostels</td>
                            <td class="text-muted">Add, edit and monitor physical residential buildings (Boys/Girls/Mixed).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    {{-- 4 Executive KPI Cards --}}
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="ast-kpi-val">{{ $totalAssets }}</div>
                        <div class="ast-kpi-lbl">Total Assets</div>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="ast-kpi-sub">
                    <a href="{{ route('school.assets.assets') }}" class="text-sky-600 font-weight-bold" style="color: #0284c7; text-decoration: none;">
                        View register <i class="fas fa-arrow-right ml-1" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #10b981;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="ast-kpi-val">₹{{ number_format($totalCost, 0) }}</div>
                        <div class="ast-kpi-lbl">Acquisition Value</div>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 8px; background: #d1fae5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="ast-kpi-sub">Original historical cost</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #6366f1;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="ast-kpi-val">₹{{ number_format($currentBookValue, 0) }}</div>
                        <div class="ast-kpi-lbl">Current Book Value</div>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 8px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="ast-kpi-sub">After depreciation</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #f59e0b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="ast-kpi-val">{{ $underMaintenanceCount }}</div>
                        <div class="ast-kpi-lbl">Under Maintenance</div>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-wrench"></i>
                    </div>
                </div>
                <div class="ast-kpi-sub">{{ $openLogsCount }} open · {{ $overdueCount }} overdue</div>
            </div>
        </div>
    </div>

    {{-- By Status Bar --}}
    <div class="ast-card mb-4">
        <div class="ast-card-header">
            <h4 class="ast-card-title"><i class="fas fa-layer-group text-primary"></i> By Status</h4>
        </div>
        <div class="ast-card-body py-3">
            <div class="d-flex flex-wrap" style="gap: 12px;">
                <span class="ast-badge ast-badge-in_store" style="font-size: 0.85rem; padding: 6px 14px;">
                    In Store: <strong>{{ $statusCounts['in_store'] }}</strong>
                </span>
                <span class="ast-badge ast-badge-in_use" style="font-size: 0.85rem; padding: 6px 14px;">
                    In Use: <strong>{{ $statusCounts['in_use'] }}</strong>
                </span>
                <span class="ast-badge ast-badge-under_maintenance" style="font-size: 0.85rem; padding: 6px 14px;">
                    Under Maintenance: <strong>{{ $statusCounts['under_maintenance'] }}</strong>
                </span>
                <span class="ast-badge ast-badge-retired" style="font-size: 0.85rem; padding: 6px 14px;">
                    Retired: <strong>{{ $statusCounts['retired'] }}</strong>
                </span>
                <span class="ast-badge ast-badge-disposed" style="font-size: 0.85rem; padding: 6px 14px;">
                    Disposed: <strong>{{ $statusCounts['disposed'] }}</strong>
                </span>
                <span class="ast-badge ast-badge-lost" style="font-size: 0.85rem; padding: 6px 14px;">
                    Lost: <strong>{{ $statusCounts['lost'] }}</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- Grid 2x2 --}}
    <div class="row">
        {{-- Card 1: Maintenance Due & Overdue --}}
        <div class="col-lg-6 mb-4">
            <div class="ast-card h-100">
                <div class="ast-card-header">
                    <h4 class="ast-card-title">
                        <i class="fas fa-tools text-warning"></i> Maintenance Due & Overdue
                    </h4>
                    <a href="{{ route('school.assets.maintenance') }}" class="btn btn-xs btn-outline-secondary">View all</a>
                </div>
                <div class="ast-card-body p-0 table-responsive">
                    <table class="ast-table">
                        <thead>
                            <tr>
                                <th>Asset</th>
                                <th>Task</th>
                                <th>Type</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenanceDue as $m)
                                <tr>
                                    <td>
                                        <a href="{{ route('school.assets.assets.show', $m->asset_id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                            {{ $m->asset->name ?? '—' }}
                                        </a>
                                        <br><span class="ast-code">{{ $m->asset->asset_tag ?? '' }}</span>
                                    </td>
                                    <td>{{ $m->title }}</td>
                                    <td><span class="badge badge-light border">{{ $m->maintenance_type }}</span></td>
                                    <td>
                                        @php
                                            $isOverdue = $m->next_due_date < \Carbon\Carbon::today();
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                            {{ $m->next_due_date->format('d M Y') }}
                                            @if($isOverdue) <span class="badge badge-danger ml-1">overdue</span> @endif
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No maintenance tasks scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card 2: Assets by Category --}}
        <div class="col-lg-6 mb-4">
            <div class="ast-card h-100">
                <div class="ast-card-header">
                    <h4 class="ast-card-title">
                        <i class="fas fa-tags text-primary"></i> Assets by Category
                    </h4>
                    <a href="{{ route('school.assets.categories') }}" class="btn btn-xs btn-outline-secondary">Manage</a>
                </div>
                <div class="ast-card-body p-0 table-responsive">
                    <table class="ast-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-right">Assets</th>
                                <th style="width: 40%;">Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryBreakdown as $cat)
                                @php
                                    $pct = $totalAssets > 0 ? round(($cat->assets_count / $totalAssets) * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="font-weight-600">{{ $cat->name }}</td>
                                    <td class="text-right font-weight-bold">{{ $cat->assets_count }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px; border-radius: 4px;">
                                            <div class="progress-bar bg-info" style="width: {{ $pct }}%;"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No categories configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card 3: Warranty Expiring Soon --}}
        <div class="col-lg-6 mb-4">
            <div class="ast-card h-100">
                <div class="ast-card-header">
                    <h4 class="ast-card-title">
                        <i class="fas fa-shield-alt text-warning"></i> Warranty Expiring (next 60 days)
                    </h4>
                    <a href="{{ route('school.assets.reports.show', 'warranty') }}" class="btn btn-xs btn-outline-secondary">Report</a>
                </div>
                <div class="ast-card-body p-0 table-responsive">
                    <table class="ast-table">
                        <thead>
                            <tr>
                                <th>Asset</th>
                                <th>Tag</th>
                                <th>Provider</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warrantyExpiring as $w)
                                <tr>
                                    <td>
                                        <a href="{{ route('school.assets.assets.show', $w->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                            {{ $w->name }}
                                        </a>
                                    </td>
                                    <td><span class="ast-code">{{ $w->asset_tag }}</span></td>
                                    <td>{{ $w->warranty_provider ?? '—' }}</td>
                                    <td>
                                        <span class="text-warning font-weight-bold">
                                            {{ $w->warranty_expiry_date ? $w->warranty_expiry_date->format('d M Y') : '—' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No warranties expiring in the next 60 days.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card 4: Recently Registered --}}
        <div class="col-lg-6 mb-4">
            <div class="ast-card h-100">
                <div class="ast-card-header">
                    <h4 class="ast-card-title">
                        <i class="fas fa-plus-circle text-success"></i> Recently Registered
                    </h4>
                    <a href="{{ route('school.assets.assets') }}" class="btn btn-xs btn-outline-secondary">All Assets</a>
                </div>
                <div class="ast-card-body p-0 table-responsive">
                    <table class="ast-table">
                        <thead>
                            <tr>
                                <th>Tag</th>
                                <th>Asset</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAssets as $ra)
                                <tr>
                                    <td>
                                        <a href="{{ route('school.assets.assets.show', $ra->id) }}" class="ast-code" style="text-decoration: none;">
                                            {{ $ra->asset_tag }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('school.assets.assets.show', $ra->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                            {{ $ra->name }}
                                        </a>
                                        @if($ra->brand || $ra->model_number)
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $ra->brand }} {{ $ra->model_number }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $ra->category->name ?? '—' }}</td>
                                    <td>
                                        <span class="ast-badge ast-badge-{{ $ra->status }}">
                                            {{ $ra->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No assets registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


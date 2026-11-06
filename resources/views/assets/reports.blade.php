@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="row">
        {{-- Report 1: Asset Register --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Asset Register</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Full list of every fixed asset with category, status, condition, historical cost and book value.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'register') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'register') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 2: Depreciation Register --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line-down"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Depreciation Register</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Every posted depreciation period, opening values, expense calculations and accumulated values.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'depreciation') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'depreciation') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 3: Custody / Allocation Register --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Custody / Allocation</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Who holds (or held) each asset, handover dates, return status and custody timeline.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'custody') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'custody') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 4: Maintenance History --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fce7f3; color: #db2777; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Maintenance History</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        All maintenance work logs, problems reported, resolutions, technician / vendor and expenses.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'maintenance') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'maintenance') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 5: Warranty Expiry --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #ccfbf1; color: #0d9488; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Warranty Expiry</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Assets with active manufacturer or vendor warranties, sorted with soonest expiry first.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'warranty') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'warranty') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 6: Disposal Register --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Disposal Register</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Disposed, sold, scrapped or donated assets, realised salvage value and net gain/loss on disposal.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'disposals') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'disposals') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Report 7: Movement Ledger --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="ast-card h-100 d-flex flex-column justify-content-between">
                <div class="ast-card-body">
                    <div class="d-flex align-items-center mb-2" style="gap: 10px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="font-weight-700 text-dark mb-0">Movement Ledger</h5>
                    </div>
                    <p class="text-muted" style="font-size: 0.88rem;">
                        Comprehensive chronological lifecycle audit trail of registrations, allocations, returns and updates.
                    </p>
                </div>
                <div class="p-3 border-top bg-light d-flex justify-content-end" style="gap: 8px;">
                    <a href="{{ route('school.assets.reports.show', 'movements') }}" class="btn btn-xs btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('school.assets.reports.export', 'movements') }}" class="btn btn-xs btn-outline-success">
                        <i class="fas fa-file-csv mr-1"></i> CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


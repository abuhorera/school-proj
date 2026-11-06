@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    {{-- Top 3 Metric Cards --}}
    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="ast-kpi-val">{{ $depreciableAssetsCount }}</div>
                <div class="ast-kpi-lbl">Depreciable Assets</div>
                <div class="ast-kpi-sub">Active assets with depreciation enabled</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #f59e0b;">
                <div class="ast-kpi-val">₹{{ number_format($accumulatedDepreciation, 2) }}</div>
                <div class="ast-kpi-lbl">Accumulated Depreciation</div>
                <div class="ast-kpi-sub">Total depreciation posted to date</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="ast-kpi-card" style="border-left: 4px solid #10b981;">
                <div class="ast-kpi-val">₹{{ number_format($netBookValue, 2) }}</div>
                <div class="ast-kpi-lbl">Net Book Value</div>
                <div class="ast-kpi-sub">Total carrying value on balance sheet</div>
            </div>
        </div>
    </div>

    {{-- Run Depreciation Card --}}
    <div class="ast-card mb-4" style="background: #f8fafc;">
        <div class="ast-card-body">
            <form method="POST" action="{{ route('school.assets.depreciation.run') }}" class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 15px;">
                @csrf
                <div>
                    <h5 class="font-weight-700 mb-1 text-dark">Run Depreciation</h5>
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">
                        Computes straight-line (SLM) and written-down value (WDV) depreciation for all eligible assets up to selected month.
                    </p>
                </div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <label class="font-weight-600 mb-0 text-nowrap" style="font-size: 0.88rem;">Post depreciation up to:</label>
                    <input type="date" name="as_of" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" style="width: 170px;">
                    <button type="submit" class="btn btn-sm btn-ast-primary text-nowrap" onclick="return confirm('Run and post depreciation for all eligible assets?');">
                        <i class="fas fa-play mr-1"></i> Run Depreciation
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Depreciation Ledger Table --}}
    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <h4 class="ast-card-title"><i class="fas fa-chart-line-down text-primary"></i> Depreciation Ledger</h4>
            <form method="GET" action="{{ route('school.assets.depreciation') }}" class="d-flex align-items-center" style="gap: 8px;">
                <select name="fiscal_year" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">All Financial Years</option>
                    @foreach($fiscalYears as $fy)
                        <option value="{{ $fy }}" {{ request('fiscal_year') == $fy ? 'selected' : '' }}>FY {{ $fy }}</option>
                    @endforeach
                </select>
                @if(request('fiscal_year'))
                    <a href="{{ route('school.assets.depreciation') }}" class="btn btn-xs btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Period</th>
                        <th>FY</th>
                        <th>Method</th>
                        <th class="text-right">Opening</th>
                        <th class="text-right">Depreciation</th>
                        <th class="text-right">Accumulated</th>
                        <th class="text-right">Closing</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                        <tr>
                            <td>
                                @if($rec->asset)
                                    <a href="{{ route('school.assets.assets.show', $rec->asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $rec->asset->name }}
                                    </a>
                                    <br><span class="ast-code">{{ $rec->asset->asset_tag }}</span>
                                @else
                                    <span class="text-muted">Asset #{{ $rec->asset_id }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-light border">{{ $rec->period_name }}</span></td>
                            <td>{{ $rec->fiscal_year }}</td>
                            <td style="font-size: 0.8rem;">{{ $rec->method }}</td>
                            <td class="text-right">₹{{ number_format($rec->opening_value, 2) }}</td>
                            <td class="text-right font-weight-bold text-danger">₹{{ number_format($rec->depreciation_amount, 2) }}</td>
                            <td class="text-right text-muted">₹{{ number_format($rec->accumulated_depreciation, 2) }}</td>
                            <td class="text-right font-weight-bold text-primary">₹{{ number_format($rec->closing_value, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                No depreciation records posted yet. Click "Run Depreciation" above to calculate and post.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


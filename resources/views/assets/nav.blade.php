@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .ast-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
    }
    .ast-title-area {
        margin-bottom: 20px;
    }
    .ast-title-area h3 {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.5rem;
        margin-bottom: 3px;
    }
    .ast-title-area p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .ast-tabs-wrap {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 22px;
        overflow-x: auto;
    }
    .ast-tabs {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 0 16px;
        flex: 1 1 auto;
        min-width: 0;
    }
    .ast-tab {
        padding: 10px 4px;
        color: #64748b;
        font-weight: 500;
        text-decoration: none !important;
        position: relative;
        font-size: 0.88rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.15s ease;
    }
    .ast-tab:hover {
        color: #0284c7;
    }
    .ast-tab.active {
        color: #0284c7;
        font-weight: 600;
    }
    .ast-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2.5px;
        background-color: #0284c7;
        border-radius: 2px 2px 0 0;
    }

    .btn-ast-primary {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-ast-primary:hover {
        background-color: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
    }

    .ast-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    .ast-card-header {
        padding: 14px 18px;
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ast-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ast-card-body {
        padding: 18px;
    }

    .ast-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
    }
    .ast-kpi-val {
        font-size: 1.65rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .ast-kpi-lbl {
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748b;
        margin-top: 4px;
    }
    .ast-kpi-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 4px;
    }

    .ast-table {
        width: 100%;
        margin-bottom: 0;
        color: #334155;
        font-size: 0.88rem;
    }
    .ast-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 14px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
    }
    .ast-table td {
        padding: 11px 14px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }
    .ast-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .ast-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 0.76rem;
        font-weight: 600;
        border-radius: 9999px;
        text-transform: capitalize;
    }
    .ast-badge-in_use { background-color: #e0f2fe; color: #0369a1; }
    .ast-badge-in_store { background-color: #f1f5f9; color: #475569; }
    .ast-badge-under_maintenance { background-color: #fef3c7; color: #b45309; }
    .ast-badge-retired { background-color: #f3f4f6; color: #374151; }
    .ast-badge-disposed { background-color: #fee2e2; color: #b91c1c; }
    .ast-badge-lost { background-color: #fef2f2; color: #991b1b; }

    .ast-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.82rem;
        padding: 2px 6px;
        background-color: #f1f5f9;
        color: #0f172a;
        border-radius: 4px;
        font-weight: 600;
    }

    /* Modal standard styles */
    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 18px;
    }
    .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #0f172a;
    }
    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 12px 18px;
    }
</style>
@endpush

<div class="ast-container">
    {{-- Title and Header Action --}}
    <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap" style="gap: 12px;">
        <div class="ast-title-area">
            <h3>Asset Management</h3>
            <p>Register, tag, allocate, depreciate and maintain every fixed asset across the school.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <a href="{{ route('school.assets.assets.tags.print') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-qrcode mr-1"></i> Print Tags
            </a>
            <a href="{{ route('school.assets.assets.create') }}" class="btn btn-sm btn-ast-primary">
                <i class="fas fa-plus mr-1"></i> Register Asset
            </a>
        </div>
    </div>

    {{-- Sub-navigation Tabs --}}
    <div class="ast-tabs-wrap">
        <div class="ast-tabs">
            <a href="{{ route('school.assets.dashboard') }}" class="ast-tab {{ request()->routeIs('school.assets.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie mr-1"></i> Dashboard
            </a>
            <a href="{{ route('school.assets.assets') }}" class="ast-tab {{ request()->routeIs('school.assets.assets*') && !request()->routeIs('school.assets.assets.tags*') ? 'active' : '' }}">
                <i class="fas fa-cubes mr-1"></i> Asset Register
            </a>
            <a href="{{ route('school.assets.categories') }}" class="ast-tab {{ request()->routeIs('school.assets.categories*') ? 'active' : '' }}">
                <i class="fas fa-tags mr-1"></i> Categories
            </a>
            <a href="{{ route('school.assets.assignments') }}" class="ast-tab {{ request()->routeIs('school.assets.assignments*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding mr-1"></i> Allocations
            </a>
            <a href="{{ route('school.assets.depreciation') }}" class="ast-tab {{ request()->routeIs('school.assets.depreciation*') ? 'active' : '' }}">
                <i class="fas fa-chart-line-down mr-1"></i> Depreciation
            </a>
            <a href="{{ route('school.assets.maintenance') }}" class="ast-tab {{ request()->routeIs('school.assets.maintenance*') ? 'active' : '' }}">
                <i class="fas fa-tools mr-1"></i> Maintenance
            </a>
            <a href="{{ route('school.assets.disposals') }}" class="ast-tab {{ request()->routeIs('school.assets.disposals*') ? 'active' : '' }}">
                <i class="fas fa-trash-alt mr-1"></i> Disposals
            </a>
            <a href="{{ route('school.assets.audits') }}" class="ast-tab {{ request()->routeIs('school.assets.audits*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check mr-1"></i> Audits
            </a>
            <a href="{{ route('school.assets.reports') }}" class="ast-tab {{ request()->routeIs('school.assets.reports*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice mr-1"></i> Reports
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 6px;">
            <i class="fas fa-check-circle mr-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 6px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-radius: 6px;">
            <div class="font-weight-bold mb-1"><i class="fas fa-times-circle mr-1"></i> Please correct the following errors:</div>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

